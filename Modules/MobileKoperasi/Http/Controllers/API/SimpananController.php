<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Anggota\Entities\Anggota;
use Modules\Simpanan\Entities\Wallet;
use Modules\Simpanan\Entities\SimkopTransaksi;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Date;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

class SimpananController extends Controller
{

    
     /**
     * Get List Simpanan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;
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


    /**
     * Get Detail Simpanan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detail($slug,Request $request)
    {
        $response = collect();
        $anggota_id = $request->user()->anggota_id;
        $wallet = Wallet::where('anggota_id', $anggota_id)->first();

        if($request->slug == 'pokok'){

            $response = $response->merge([
                'program' => 'Simpanan Pokok',
                'saldo' => number_format($wallet->pokok,0,',','.'),
                'slug' => 'pokok',
            ]);
            $riwayat = Transaksi::select('transaksi.no_transaksi', 'transaksi_kas.tgl', 'transaksi.jenis', 'transaksi_kas.jumlah')
            ->join('transaksi_kas', function($join)
            {
                $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
            })
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('transaksi_kas.akun_id', 3)
            ->get();

            $riwayat->each(function ($data) {
                $data->jumlah_currency = 'Rp '. number_format($data->jumlah,0,',','.');
                $data->jumlah = number_format($data->jumlah,0,',','.');
                $data->tgl = Date::parse($data->tgl)->format('d-m-Y');
            });
            $response->put('transaksi',$riwayat);

        }elseif($request->slug == 'wajib'){

            $response = $response->merge([
                'program' => 'Simpanan Wajib',
                'saldo' => number_format($wallet->wajib,0,',','.'),
                'slug' => 'wajib',
            ]);
            $riwayat = Transaksi::select('transaksi.no_transaksi', 'transaksi_kas.tgl', 'transaksi.jenis', 'transaksi_kas.jumlah')
            ->join('transaksi_kas', function($join)
            {
                $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
            })
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('transaksi_kas.akun_id', 4)
            ->orderBy('tgl', 'DESC')
            ->limit(10)
            ->get();

            $riwayat->each(function ($data) {
                $data->jumlah_currency = 'Rp '. number_format($data->jumlah,0,',','.');
                $data->jumlah = number_format($data->jumlah,0,',','.');
                $data->tgl = Date::parse($data->tgl)->format('d-m-Y');
            });

            $response->put('transaksi',$riwayat);
            

        }elseif($request->slug == 'sukarela'){
            $response = $response->merge([
                'program' => 'Simpanan Sukarela',
                'saldo' => number_format($wallet->sukarela,0,',','.'),
                'slug' => 'sukarela',
            ]);

            $riwayat = Transaksi::select('transaksi.no_transaksi', 'transaksi_kas.tgl', 'transaksi.jenis', 'transaksi_kas.jumlah')
            ->join('transaksi_kas', function($join)
            {
                $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
            })
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('transaksi_kas.akun_id', 14)
            ->orderBy('tgl', 'DESC')
            ->limit(10)
            ->get();

            $riwayat->each(function ($data) {
                $data->jumlah_currency = 'Rp '. number_format($data->jumlah,0,',','.');
                $data->jumlah = number_format($data->jumlah,0,',','.');
                $data->tgl = Date::parse($data->tgl)->format('d-m-Y');
            });

            $response->put('transaksi',$riwayat);

        }elseif($request->slug == 'sosial'){

            $response = $response->merge([
                'program' => 'Simpanan Sosial',
                'saldo' => number_format($wallet->sosial,0,',','.'),
                'slug' => 'sosial',
            ]);

            $riwayat = Transaksi::select('transaksi.no_transaksi', 'transaksi_kas.tgl', 'transaksi.jenis', 'transaksi_kas.jumlah')
            ->join('transaksi_kas', function($join)
            {
                $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
            })
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('transaksi_kas.akun_id', 9)
            ->orderBy('tgl', 'DESC')
            ->limit(10)
            ->get();

            $riwayat->each(function ($data) {
                $data->jumlah_currency = 'Rp '. number_format($data->jumlah,0,',','.');
                $data->jumlah = number_format($data->jumlah,0,',','.');
                $data->tgl = Date::parse($data->tgl)->format('d-m-Y');
            });
            $response->put('transaksi',$riwayat);
        }

        return response()->json([
            'data' => $response,
            'fail' => false,
        ], 200);
    }

    public function riwayat($slug, Request $request)
    {
        $anggota_id = $request->user()->anggota_id;
        
        if($slug == 'pokok'){
            $akun_id = 3;
        }else if($slug == 'wajib'){
            $akun_id = 4;
        }else if($slug == 'sukarela'){
            $akun_id = 14;
        }else if($slug == 'sosial'){
            $akun_id = 9;
        }

        $transaksi = Transaksi::select('transaksi.no_transaksi', 'transaksi.tgl_transaksi as tgl', 'transaksi.jenis', 'transaksi_kas.jumlah', 'transaksi_kas.akun_id', 'transaksi.metode_pembayaran')
        ->join('transaksi_kas', function($join)
        {
            $join->on('transaksi.no_transaksi', '=', 'transaksi_kas.no_transaksi');
        })
        ->where('transaksi.anggota_id', $anggota_id)
        ->where('transaksi_kas.akun_id', $akun_id)
        ->orderBy('transaksi.tgl_transaksi', 'DESC')
        ->paginate(15);
        // ->get();

        $transaksi->each(function ($data) {
            $data->jumlah_currency = 'Rp '. number_format($data->jumlah,0,',','.');
            $data->jumlah = number_format($data->jumlah,0,',','.');
            $data->tgl = Date::parse($data->tgl)->format('d-m-Y');
        });
        
        return response()->json([
            'data' => $transaksi->getCollection(),
            'fail' => false,
        ], 200);
    }


}
