<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Modules\Keuangan\Entities\Payment;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Bank\Entities\Bank;
use Date;
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

        $wallet = Wallet::where('anggota_id', $anggota_id)->first();

        return response()->json([
            'data' => currency($wallet->sukarela),
            'fail' => false,
        ], 200);
    }


    public function riwayat(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;

        $response = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'transaksi.status', 'a.jumlah')
        ->join('simla_transaksi as a', 'a.transaksi_id', '=', 'transaksi.id')
        ->with(['pembayaran' => function($q){
            $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
        }])
        ->where('a.anggota_id', $anggota_id)
        ->orderBy('tgl', 'DESC')
        ->paginate(15);

        $response->each(function ($data) {
            $data->jumlah = (int)$data->jumlah;
            $data->total = (int)$data->total;
            $data->tgl = Date::parse($data->tgl)->format('d F Y');
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
        });

        return response()->json($response, 200);
    }

    public function topup(Request $request)
    {
        // dd($request->all());
        $rules = [
            'jumlah' => 'required',
            'bank' => 'required',
        ];

        $pesan = [
            'jumlah.required' => 'Jumlah Simpanan Wajib Diisi!',
            'bank.required' => 'Tanggal Transaksi Wajib Diisi!',
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

                $kd_transaksi = generate_transaksi_kd();
                $anggota_id = $request->user()->anggota_id;
                $tgl = Date::now();

                $transaksi = new Transaksi();
                $transaksi->no_transaksi = $kd_transaksi;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->jenis = 'setoran sukarela';
                $transaksi->item = json_encode($item);
                $transaksi->total = $request->jumlah;
                $transaksi->tgl_transaksi = $tgl->format('Y-m-d');
                $transaksi->created_at = $tgl->format('Y-m-d H:i:s');
                $transaksi->save();


            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Transaksi',
                    'error' => $e,
                ]);
            }

            try{
                

                $simla = new SimlaTransaksi();
                $simla->no_transaksi = $kd_transaksi;
                $simla->anggota_id = $anggota_id;
                $simla->type = 'isi saldo';
                $simla->amount = $request->jumlah;
                $simla->status = 'pending';
                $simla->tgl = $tgl->format('Y-m-d H:i:s');
                $simla->created_at = $tgl->format('Y-m-d H:i:s');
                $simla->save();

                $payment = new Payment();
                $payment->transaksi_id = $kd_transaksi;
                $payment->jumlah = $request->jumlah;
                $payment->bank_id = $request->bank;
                $payment->code = get_payment_code($tgl);
                $payment->tgl = $tgl->format('Y-m-d H:i:s');
                $payment->created_at = $tgl->format('Y-m-d H:i:s');
                $payment->save();

                $payment->no_transaksi = $kd_transaksi;
                $payment->tgl = $tgl->format('d m Y');
                $payment->jumlah = (int)$payment->jumlah;
                $payment->bank = Bank::where('id', '=', $payment->bank_id)->first();
                $payment->bank->logo = asset($payment->bank->logo);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                    'error' => $e,
                ]);
            }

            // DB::commit();
            return response()->json([
                'fail' => false,
                'data' => $payment,
            ]);
        }
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
