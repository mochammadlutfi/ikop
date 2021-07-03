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

class TransaksiController extends Controller
{

    
     /**
     * Get Saldo Simla.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();

        return response()->json([
            'data' => currency($wallet->sukarela),
            'fail' => false,
        ], 200);
    }

    public function detail($slug, $no_transaksi, Request $request)
    {
        $response = collect();

        if($slug == 'pokok'){
            $akun_id = 3;
        }else if($slug == 'wajib'){
            $akun_id = 4;
        }else if($slug == 'sukarela'){
            $akun_id = 14;
        }else if($slug == 'sosial'){
            $akun_id = 9;
        }

        $transaksi = Transaksi::select('transaksi.no_transaksi', 'transaksi_kas.tgl', 'transaksi.jenis', 'transaksi_kas.jumlah', 'transaksi_kas.akun_id', 'transaksi.metode_pembayaran')
        ->join('transaksi_kas', function($join)
        {
            $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
        })
        ->where('transaksi.no_transaksi', $no_transaksi)
        ->where('transaksi_kas.akun_id', $akun_id)
        ->first();

        $transaksi->jumlah_currency = 'Rp '. number_format($transaksi->jumlah,0,',','.');
        $transaksi->jumlah = number_format($transaksi->jumlah,0,',','.');
        $transaksi->tgl = Date::parse($transaksi->tgl)->format('d-m-Y');

        $response = $response->merge($transaksi);

        $rincian = Collect([
            ['label' => 'Jumlah', 'value' => $transaksi->jumlah_currency],
            ['label' => 'Metode', 'value' => ucwords($transaksi->metode_pembayaran)],
            ['label' => 'No Transaksi', 'value' => $transaksi->no_transaksi],
            ['label' => 'Tanggal', 'value' => Date::parse($transaksi->tgl)->format('d F Y')]
        ]);

        $response->put('rincian', $rincian);
        

        return response()->json([
            'data' => $response,
            'fail' => false,
        ], 200);
    }

}
