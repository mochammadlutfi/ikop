<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Date;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

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

        $response = Transaksi::select('transaksi.no_transaksi', 'transaksi_kas.tgl', 'transaksi.jenis', 'transaksi_kas.jumlah')
        ->join('transaksi_kas', function($join)
        {
            $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
        })
        ->where('transaksi.anggota_id', $anggota_id)
        ->where('transaksi_kas.akun_id', 14)
        ->orderBy('tgl', 'DESC')
        ->limit(15)
        ->get();

        $response->each(function ($data) {
            $data->jumlah_currency = 'Rp '. number_format($data->jumlah,0,',','.');
            $data->jumlah = number_format($data->jumlah,0,',','.');
            $data->tgl = Date::parse($data->tgl)->format('d-m-Y');
        });

        return response()->json([
            'data' => $response,
            'fail' => false,
        ], 200);
    }

    public function transaksi(Request $request)
    {
        $wallet = Wallet::where('anggota_id', $anggota_id)->first();
        $simpanan = collect([
            [
                'program' => 'Simpanan Pokok',
                'saldo' => currency($wallet->pokok),
                'slug' => 'pokok',
            ],
            [
                'program' => 'Simpanan Wajib',
                'saldo' => currency($wallet->wajib),
                'slug' => 'wajib',
            ],
            [
                'program' => 'Simpanan Sosial',
                'saldo' => currency($wallet->sosial),
                'slug' => 'sosial',
            ],
            [
                'program' => 'Simpanan Sukarela',
                'saldo' => currency($wallet->sukarela),
                'slug' => 'sukarela',
            ],
        ]);
        
        return response()->json([
            'data' => $simpanan,
            'fail' => false,
        ], 200);
    }

}
