<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Simpanan\Entities\SimkopTransaksi;
use Modules\Keuangan\Entities\TransaksiBayar;
use Date;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{

    
     /**
     * Get Saldo Simla.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {

        $anggota_id = $request->user()->anggota_id;
        if($request->status == 'aktif'){
            $response = Transaksi::select('transaksi.id', 'transaksi.total', 'transaksi.service', 'transaksi.sub_service', 'transaksi.jenis', 'transaksi.tgl', 'transaksi.status')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
                $q->with(['bank:id,logo']);
            }])
            ->where('transaksi.status', 0)
            ->where('transaksi.anggota_id', $anggota_id)
            ->orderBy('transaksi.tgl', 'DESC')
            ->paginate(15);
        }else{
            $response = Transaksi::select('transaksi.id', 'transaksi.total', 'transaksi.service', 'transaksi.sub_service', 'transaksi.jenis', 'transaksi.tgl', 'transaksi.status')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
                $q->with(['bank:id,logo']);
            }])
            ->where('transaksi.status', 1)
            ->where('transaksi.anggota_id', $anggota_id)
            ->orderBy('transaksi.tgl', 'DESC')
            ->paginate(15);
        }

        $response->each(function ($data) {
            $data->pembayaran->admin_fee = (int)$data->pembayaran->admin_fee;
            $data->pembayaran->jumlah = (int)$data->pembayaran->jumlah;
            $data->total = $data->jenis == 'transfer sukarela' ? (int)-$data->total : (int)$data->total;
            $data->tgl = Date::parse($data->tgl)->format('d F Y');
            if($data->pembayaran->method == 'Simpanan Sukarela' && $data->status == 0){
                $data->pembayaran->status = 'Diproses';
            }else{
                if($data->pembayaran->status === 'pending'){
                    $data->pembayaran->status = 'Menunggu Pembayaran';
                }else if($data->pembayaran->status === 'draft'){
                    $data->pembayaran->status = 'Verifikasi';
                }else if($data->pembayaran->status === 'confirm'){
                    $data->pembayaran->status = 'Berhasil';
                }else{
                    $data->pembayaran->status = 'Dibatalkan';
                }
            }
        });

        return response()->json($response, 200);
    }

    public function detail($id, Request $request)
    {

        $data = Transaksi::select('transaksi.id', 'transaksi.total', 'transaksi.nomor', 'transaksi.service', 'transaksi.jenis', 'transaksi.sub_service', 'transaksi.tgl', 'transaksi.status')->
        with(['pembayaran' => function($q){
            $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'jumlah']);
            $q->with(['bank:id,logo']);
        }, 'ppob', 'simkop'])
        ->where('transaksi.id', $id)
        ->first();

        $data->total = (int)$data->total;
        $data->pembayaran->admin_fee = (int)$data->pembayaran->admin_fee;
        $data->pembayaran->jumlah = (int)$data->pembayaran->jumlah;
        $data->item = json_decode($data->item);

        return response()->json([
            'data' => $data,
            'fail' => false,
        ], 200);
    }


    public function fix()
    {
        try{
            $data = TransaksiKas::select('transaksi_kas.*','a.anggota_id', 'a.tgl as tgl_bayar')
            ->join('transaksi as a', 'a.id', '=', 'transaksi_kas.transaksi_id')
            ->where('transaksi_kas.akun_id', 14)
            ->orderBy('transaksi_kas.id', 'ASC')->get();
            // $data = SimkopTransaksi::select('simkop_transaksi.id as simkop_id','transaksi.*', 'simkop_transaksi.periode')
            // ->join('transaksi', 'transaksi.no_transaksi', '=', 'simkop_transaksi.no_transaksi')
            // ->orderBy('simkop_transaksi.id', 'ASC')->get();
            // $data = Transaksi::where('akun_id', 14)->orderBy('id', 'ASC')->get();
            foreach($data as $d){
                // TransaksiBayar::where('no_transaksi', $d->no_transaksi)->update(array('transaksi_id' => $d->id));
                // $bayar = new TransaksiBayar();
                // $bayar->transaksi_id = $d->id;
                // $bayar->method = 'Tunai';
                // $bayar->jumlah = $d->total;
                // $bayar->tgl_bayar = $d->tgl;
                // $bayar->status = 'confirm';
                // $bayar->save();
                $simla = new SimlaTransaksi();
                $simla->transaksi_id = $d->transaksi_id;
                $simla->anggota_id = $d->anggota_id;
                $simla->type = $d->jenis == 'pemasukan' ?  'isi saldo' : 'penarikan';
                $simla->jumlah = $d->jumlah;
                $simla->save();

                // $trans = Transaksi::where('no_transaksi', $d->no_transaksi)->first(); 
                // $trans->nomor = get_simkop_nomor($d->periode);
                // if($d->jenis != 'pendaftaran'){
                //     $trans->service = 'simpanan';
                //     $trans->sub_service = 'koperasi';
                // }
                // $trans->save();
                // $kop = SimkopTransaksi::where('no_transaksi', $d->no_transaksi)->first();
                // $kop->transaksi_id = $d->id;
                // $kop->save();
            }
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                'error' => $e,
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'data' => $data
        ]);
    }

}
