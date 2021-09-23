<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Anggota\Entities\Anggota;
use Modules\Simpanan\Entities\Wallet;
use Modules\Simpanan\Entities\SimkopTransaksi;
use Modules\Pembiayaan\Entities\PmbTunai;
use Modules\Pembiayaan\Entities\PmbTunaiDetail;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Date;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use DB;
class PembiayaanTunaiController extends Controller
{

    
     /**
     * Get List Simpanan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;
        try{
            $data = PmbTunai::select('no_pembiayaan', 'id', 'durasi', 'jumlah', 'status', 'created_at')
            ->where('anggota_id', $anggota_id)
            ->paginate(15);
            $data->each(function ($data) {
                $data->jumlah = (int)$data->jumlah;
                $data->tgl_pengajuan =Date::parse($data->created_at)->format('d F Y');
            });
            
            return response()->json([
                'data' => $data,
                'fail' => false,
            ], 200);
        }catch(\QueryException $e){
            return response()->json([
                'message' => "Terjadi Kesalahan Server!",
                'fail' => true,
            ], 400);
        }
    }

    /**
     * Get Detail Simpanan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function list_detail($slug,Request $request)
    {
        $response = collect();
        $anggota_id = $request->user()->anggota_id;
        $wallet = Wallet::where('anggota_id', $anggota_id)->first();

        if($request->slug == 'tunai'){
            $tunai_aktif = PmbTunai::where('anggota_id', $anggota_id)->where('status', 1)->sum('jumlah');
            $limit = $wallet->pokok + $wallet->wajib - $tunai_aktif;

            $now = Date::now();
            $tagihan = PmbTunai::select('pmb_tunai.anggota_id', 'pmb_tunai_detail.total', 'pmb_tunai_detail.tgl_tempo', 'pmb_tunai_detail.id')
            ->join('pmb_tunai_detail', 'pmb_tunai_detail.pmb_tunai_id', '=', 'pmb_tunai.id')
            ->where('anggota_id', $anggota_id)->where('pmb_tunai_detail.status', 0)
            // $tagihan = PmbTunaiDetail::
            ->whereMonth('tgl_tempo', $now->format('m'))->whereYear('tgl_tempo', $now->format('Y'));

            $response = $response->merge([
                'program' => 'Pembiayaan Tunai',
                'limit' => number_format($limit,0,',','.'),
                'slug' => 'tunai',
                'jumlah_tagihan' => (int)$tagihan->sum('total')
            ]);

            $pengajuan = PmbTunai::select('no_pembiayaan', 'id', 'durasi', 'jumlah', 'status', 'created_at')->where('anggota_id', $anggota_id)->where('status', "pending")->limit(2)->get();
            $pengajuan->each(function ($data) {
                $data->jumlah = (int)$data->jumlah;
            });
            $response->put('pengajuan', $pengajuan);

            $tagihan = $tagihan->get();
            $tagihan->each(function ($data) {
                $data->total = (int)$data->total;
                $data->tgl_tempo = Date::parse($data->tgl_tempo)->format('d F Y');
            });
            $response->put('angsuran', $tagihan);

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

    public function pengajuan(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;

        if($request->slug == 'tunai'){
            DB::beginTransaction();
            try{

                $jumlah_bagihasil = $request->jumlah * 3.95 / 100;
                $angsuran_pokok = $request->jumlah/$request->durasi;

                $data = new PmbTunai();
                $data->anggota_id = $anggota_id;
                $data->no_pembiayaan = generate_pembiayaan_no('tunai');
                $data->durasi = (int)$request->durasi;
                $data->jumlah = (int)$request->jumlah;
                $data->jumlah_bunga = $jumlah_bagihasil;
                $data->angsuran_pokok = $angsuran_pokok;
                $data->angsuran_bunga = $jumlah_bagihasil;
                $data->status = "pending";
                $data->biaya_admin = $request->jumlah * 1/100;
                $data->save();
                $data->slug = 'tunai';

                $response = collect([
                    'id' => $data->id,
                    'slug' => 'tunai',
                ]);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                    'error' => $e,
                ], 403);
            }
        }
        DB::commit();
        return response()->json([
            'data' => $response,
            'fail' => false,
        ], 200);

    }

    public function detail($slug,$id, Request $request)
    {
        $response = collect();
        $anggota_id = $request->user()->anggota_id;
        if($slug == 'tunai'){
            $data = PmbTunai::select('id', 'no_pembiayaan', 'durasi', 'jumlah', 'jumlah_bunga', 'biaya_admin', 'status', 'created_at')
            ->where('id', $id)->first();
            $data->jumlah = (int)$data->jumlah;
            $data->jumlah_bunga = (int)$data->jumlah_bunga;
            $data->biaya_admin = (int)$data->biaya_admin;
            $data->tgl_pengajuan = Date::parse($data->created_at)->format('d F Y');

            if($data->status == 0){
                $rincian = Collect([]);
                for($i = 1; $i <= $data->durasi; $i++){
                    $rincian[] = [
                        'tgl_tempo' => Date::parse($data->created_at)->addMonth($i)->day(10)->format('d F Y'),
                        'angsuran_ke' => $i .'/'.$data->durasi,
                        'total' => ($data->jumlah / $data->durasi) + $data->jumlah_bunga,
                        'status' => 0,
                    ];
                }
                $data->rincian = $rincian;
            }
        }
        return response()->json([
            'data' => $data,
            'fail' => false,
        ], 200);
    }

    public function tagihan($slug,Request $request)
    {
        $response = collect();
        $anggota_id = $request->user()->anggota_id;
        if($slug == 'tunai'){
            $now = Date::now();

            $data = PmbTunai::select('pmb_tunai.anggota_id', 'pmb_tunai.no_pembiayaan', 'pmb_tunai_detail.total', 'pmb_tunai_detail.tgl_tempo', 'pmb_tunai_detail.id', 'pmb_tunai_detail.angsuran_ke')
            ->join('pmb_tunai_detail', 'pmb_tunai_detail.pmb_tunai_id', '=', 'pmb_tunai.id')
            ->where('anggota_id', $anggota_id)->where('pmb_tunai_detail.status', 0)
            ->whereMonth('tgl_tempo', $now->format('m'))->whereYear('tgl_tempo', $now->format('Y'))->get();

            // $data->rincian = $rincian;
            $data->each(function ($data) {
                $data->total = (int)$data->total;
                $data->tgl_tempo = Date::parse($data->tgl_tempo)->format('d M Y');
            });
        }
        return response()->json([
            'data' => $data,
            'fail' => false,
        ], 200);
    }




}
