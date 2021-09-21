<?php
namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;
use DB;
use Carbon\Carbon;


class SetoranController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function simla(Request $request)
    {
        $type = 'isi saldo';
        return view('setoran.sukarela', compact('type'));
    }
    
    public function simla_store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'jumlah' => 'required',
            'tgl' => 'required',
            'type' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'ID Anggota Wajib Diisi!',
            'kas_id.required' => 'Kas Wajib Diisi!',
            'jumlah.required' => 'Jumlah Simpanan Wajib Diisi!',
            'tgl.required' => 'Tanggal Transaksi Wajib Diisi!',
            'type.required' => 'Jenis Transaksi Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            $item = collect([
                array(
                    'keterangan' => 'Simpanan Sukarela',
                    'jumlah' => $request->jumlah,
                    'akun' => 14,
                ),
            ]);

            DB::beginTransaction();
            try{

                $transaksi = new Transaksi();
                $transaksi->nomor = $request->kd_transaksi;
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->service = 'simpanan';
                $transaksi->sub_service = 'sukarela';
                $transaksi->jenis = $request->type === 'isi saldo' ? 'setoran sukarela' : 'penarikan sukarela';
                $transaksi->teller_id   = auth()->guard('admin')->user()->id;
                $transaksi->keterangan = $request->keterangan;
                $transaksi->total = $request->jumlah;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->status = 1;
                $transaksi->save();


                $line = new TransaksiLine();
                $line->jumlah = $request->jumlah;
                $line->keterangan = 'Simpanan Sukarela';
                $line->akun_id = 14;
                $line->user_id = auth()->guard('admin')->user()->id;
                $line->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->line()->save($line);

                $payment = new TransaksiBayar();
                $payment->method = 'Tunai';
                $payment->jumlah = $request->jumlah;
                $payment->status = 'confirm';
                $transaksi->pembayaran()->save($payment);


                $simla = new SimlaTransaksi();
                $simla->anggota_id = $request->anggota_id;
                $simla->type = $request->type;
                $simla->jumlah = $request->jumlah;
                // $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->simla()->save($simla);

                $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
                if($request->type === 'deposit')
                {
                    $wallet->increment('sukarela', $request->jumlah);
                }else{
                    $wallet->decrement('sukarela', $request->jumlah);
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
                'invoice' => $transaksi->id,
            ]);
        }

    }
}