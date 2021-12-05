<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Anggota\Entities\Anggota;
use Modules\Simpanan\Entities\Wallet;
use Modules\Simpanan\Entities\SimkopTransaksi;
use Modules\Simpanan\Entities\SimlaTransaksi;


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
                'program' => 'Simpanan Wajib',
                'saldo' => currency($wallet->wajib),
                'slug' => 'wajib',
            ],
            [
                'program' => 'Simpanan Sosial',
                'saldo' => currency($wallet->sosial),
                'slug' => 'sosial',
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
            $riwayat = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'a.jumlah', 'transaksi.status')
            ->join('transaksi_kas as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
            }])
            ->where('transaksi.status', 1)
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('a.akun_id', 3)
            ->get();

        }elseif($request->slug == 'wajib'){

            $response = $response->merge([
                'program' => 'Simpanan Wajib',
                'saldo' => number_format($wallet->wajib,0,',','.'),
                'slug' => 'wajib',
            ]);
            
            $riwayat = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'a.jumlah', 'transaksi.status')
            ->join('transaksi_line as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
            }])
            ->where('transaksi.status', 1)
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('a.akun_id', 4)
            ->orderBy('tgl', 'DESC')
            ->limit(10)
            ->get();

            

            $response->put('tagihan', $this->tagihanSimkop($request));

        }elseif($request->slug == 'sukarela'){
            $response = $response->merge([
                'program' => 'Simpanan Sukarela',
                'saldo' => number_format($wallet->sukarela,0,',','.'),
                'slug' => 'sukarela',
            ]);

            $riwayat = Transaksi::select('transaksi.*', 'a.jumlah')
            ->join('simla_transaksi as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
            }])
            ->where('transaksi.status', 1)
            ->where('transaksi.anggota_id', $anggota_id)
            ->orderBy('tgl', 'DESC')
            ->limit(15)
            ->get();

        }elseif($request->slug == 'sosial'){

            $response = $response->merge([
                'program' => 'Simpanan Sosial',
                'saldo' => number_format($wallet->sosial,0,',','.'),
                'slug' => 'sosial',
            ]);

            $riwayat = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'a.jumlah', 'transaksi.status')
            ->join('transaksi_kas as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
            }])
            ->where('transaksi.status', 1)
            ->where('transaksi.anggota_id', $anggota_id)
            ->where('a.akun_id', 9)
            ->orderBy('tgl', 'DESC')
            ->limit(10)
            ->get();
        }
        

        $riwayat->each(function ($data) {
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
        $response->put('transaksi',$riwayat);

        return response()->json([
            'data' => $response,
            'fail' => false,
        ], 200);
    }

    public function riwayat($slug, Request $request)
    {
        $anggota_id = $request->user()->anggota_id;
        
        if($slug == 'sukarela'){
            $akun_id = 14;
        }else if($slug == 'wajib'){
            $akun_id = 4;
        }else if($slug == 'sukarela'){
            $akun_id = 3;
        }else if($slug == 'sosial'){
            $akun_id = 9;
        }

        if($slug == 'sukarela'){
            $transaksi = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'transaksi.status', 'a.jumlah')
            ->join('simla_transaksi as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
            }])
            ->where('transaksi.status', 1)
            ->where('a.anggota_id', $anggota_id)
            ->orderBy('tgl', 'DESC')
            ->paginate(15);
        }else{
            $transaksi = Transaksi::select('transaksi.id', 'transaksi.jenis', 'transaksi.service', 'transaksi.tgl', 'transaksi.total', 'transaksi.status', 'a.jumlah')
            ->join('transaksi_kas as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'status', 'jumlah']);
            }])
            ->where('transaksi.status', 1)
            ->where('a.akun_id', $akun_id)
            ->where('transaksi.anggota_id', $anggota_id)
            ->orderBy('transaksi.tgl', 'DESC')
            ->paginate(15);
        }
        $transaksi->each(function ($data) {
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
        
        return response()->json([
            'data' => $transaksi->getCollection(),
            'fail' => false,
        ], 200);
    }


    /**
     * Get List Simpanan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function tagihanSimkop(Request $request){
        
        $tgl_gabung = $request->user()->anggota->tgl_gabung;
        $anggota_id = $request->user()->anggota_id;
        $dari = Date::parse($tgl_gabung)->startOfMonth()->year('2021');
        $now = Date::now()->endOfMonth();
        $diff_in_months = $dari->diffInMonths($now);

        $nominal = 0;
        $jumlah = 0;
        $list = collect([]);
        for($i = 0; $i <= $diff_in_months; $i++)
        {
            $bulan = SimkopTransaksi::where('anggota_id', $anggota_id)
            ->whereMonth('periode', $dari->format('m'))
            ->whereYear('periode', $dari->format('Y'))
            ->first();
            if(!$bulan)
            {
                $nominal += 100000;
                $jumlah += 1;
                $list->push($dari->format('d-m-Y'));
            }
            $dari->addMonth(1);
        }

        $sorted = $list->sortDesc();

        $data = collect([
            'jumlah' => $jumlah,
            'nominal' => (int)$nominal,
            'list' => $sorted->values()->all(),
        ]);

        return $data;
    }


}
