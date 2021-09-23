<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Modules\Anggota\Entities\Anggota;
use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiLine;
use Modules\Keuangan\Entities\Payment;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Bank\Entities\Bank;

use App\Helpers\Notification;
use App\Models\User;
use Date;
use Hash;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use DB;

class SukarelaController extends Controller
{
    
     /**
     * Get Saldo Simla.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saldo(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;

        $saldo = SimlaTransaksi::
        leftJoin('transaksi as a', 'a.id', '=', 'simla_transaksi.transaksi_id')
        ->leftJoin('transaksi_bayar as b', 'b.transaksi_id', '=', 'simla_transaksi.transaksi_id')
        ->where('a.status', 1)
        ->where('b.status', "confirm")
        ->where('a.anggota_id', $anggota_id)
        ->sum('simla_transaksi.jumlah');
        
        return response()->json([
            'data' =>(int)$saldo,
            'fail' => false,
        ], 200);
    }


    public function riwayat(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;

        $response = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'transaksi.status', 'a.jumlah', 'transaksi.status')
        ->join('simla_transaksi as a', 'a.transaksi_id', '=', 'transaksi.id')
        ->with(['pembayaran' => function($q){
            $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
        }])
        ->where('a.anggota_id', $anggota_id)
        ->where('transaksi.status', 1)
        ->orderBy('tgl', 'DESC')
        ->paginate(15);

        $response->each(function ($data) {
            $data->jumlah = (int)$data->jumlah;
            $data->total = (int)$data->total;
            $data->tgl = Date::parse($data->tgl)->format('d F Y');

            if($data->pembayaran == null){
                $data->pembayaran->jumlah = (int)12;

            }else{
                $data->pembayaran->jumlah = (int)$data->pembayaran->jumlah;
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

    public function transfer(Request $request)
    {
        $user = User::find($request->user()->id);
        $rules = [
            'jumlah' => 'required',
        ];

        $pesan = [
            'jumlah.required' => 'Jumlah Simpanan Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ], 203);
        }else if(Hash::check($request->secure_code, $user->secure_code)){
            $item = collect([
                array(
                    'keterangan' => 'Simpanan Sukarela',
                    'jumlah' => $request->jumlah,
                    'akun' => 14,
                ),
            ]);
            DB::beginTransaction();
            try{

                $nomor = get_simla_nomor();
                $anggota_id = $request->user()->anggota_id;
                $tgl = Date::now();

                $item = collect([
                    array(
                        'keterangan' => 'Simpanan Sukarela',
                        'jumlah' => $request->jumlah,
                        'akun' => 14,
                    ),
                ]);

                $transaksi = new Transaksi();
                $transaksi->nomor = $nomor;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->jenis = 'transfer sukarela';
                $transaksi->service = 'simpanan';
                $transaksi->sub_service = 'sukarela';
                $transaksi->keterangan = $request->keterangan;
                $transaksi->total = $request->jumlah;
                $transaksi->tgl = $tgl->format('Y-m-d H:i:s');
                $transaksi->status = 1;
                $transaksi->save();

                $simla = new SimlaTransaksi();
                $simla->anggota_id = $anggota_id;
                $simla->tujuan = $request->anggota_id;
                $simla->type = 'transfer';
                $simla->jumlah = -$request->jumlah;
                $transaksi->simla()->save($simla);

                $tujuan = Anggota::where('anggota_id', $request->anggota_id)->first();
                $tujuan->nama;
                $fcm_token = $user->device_id;
                
                $data = [
                    'title'       => 'Transfer Berhasil',
                    'description' => "Transfer Ke ".$tujuan->nama ." Berhasil",
                    'order_id'    => $transaksi->id,
                    'image'       => '',
                ];
                Notification::send_push_notif_to_device($fcm_token, $data);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'message' => 'Terjadi Error Transaksi',
                    'error' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
                'data' => $transaksi,
            ]);
        }
        return response()->json([
            'fail' => true,
            'message' => 'Security Code Salah',
        ], 203);
    }

    public function confirm(Request $request)
    {
        // $payment = Payment::where('id', $request->id)->first();
        // $payment->status = 1;
        // $payment->save();

        return response()->json([
            'fail' => false,
            'data' => $request->all(),
        ]);

    }

}
