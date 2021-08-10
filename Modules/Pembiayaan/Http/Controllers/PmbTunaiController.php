<?php

namespace Modules\Pembiayaan\Http\Controllers;

use Modules\Pembiayaan\Entities\PmbTunai;
use Modules\Pembiayaan\Entities\PmbTunaiDetail;
use Modules\Pembiayaan\Entities\PmbTunaiBayar;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Date;

class PmbTunaiController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return view('pembiayaan::tunai.pembiayaan_list');
    }


    public function data(Request $request){
        if($request->ajax()) {
            $keyword  = $request->keyword;

            $data = PmbTunai::with([
                'anggota' => function($query) use ($keyword) {
                    $query->select('anggota_id','nama');
                },
            ])
            ->whereHas('anggota', function ($query) use ($keyword) {
                return $query->where('anggota_id', 'like', '%' . $keyword . '%')
                ->orWhere('nama', 'like', '%' . $keyword . '%');
            })
            ->where(function ($query) {
                $query->where('status', 1)
                      ->orWhere('status', 3);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

            return response()->json($data, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function tagihan(Request $request)
    {
        if($request->ajax()) {
            $keyword  = $request->keyword;
            $tgl_mulai = Date::parse($request->tgl_mulai);
            $tgl_akhir = Date::parse($request->tgl_akhir);

            $data = PmbTunai::select('pmb_tunai.no_pembiayaan', 'pmb_tunai.id', 'pmb_tunai.jumlah', 'pmb_tunai.anggota_id', 'anggota.nama as anggota_nama', 'pmb_tunai_detail.angsuran_ke', 
            'pmb_tunai_detail.tgl_tempo', 'pmb_tunai_detail.total as jumlah_angsuran',)
            ->join('anggota', 'pmb_tunai.anggota_id', '=', 'anggota.anggota_id')
            ->join('pmb_tunai_detail', 'pmb_tunai_detail.pmb_tunai_id', '=', 'pmb_tunai.id')
            ->orderBy('pmb_tunai_detail.tgl_tempo', 'DESC')
            ->whereBetween('pmb_tunai_detail.tgl_tempo', [$tgl_mulai, $tgl_akhir])
            ->paginate(20);

            return response()->json($data, 200);
        }

        return view('pembiayaan::tunai.tagihan');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'jumlah' => 'required',
            'tenor' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Koperasi Wajib Diisi!',
            'jumlah.required' => 'Jumlah Wajib Diisi!',
            'tenor.required' => 'Durasi Pembiayaan Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{

                $data = new PmbTunai();
                $data->anggota_id = $request->anggota_id;
                $data->durasi = $request->tenor;
                $data->jumlah = $request->jumlah;
                $data->jumlah_bunga = $request->jumlah_bunga;
                $data->angsuran_pokok = $request->angsuran_pokok;
                $data->angsuran_bunga = $request->angsuran_bunga;
                $data->status = 0;
                $data->biaya_admin = $request->biaya_admin;
                $data->save();


                
                

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
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

        $data = PmbTunai::with([
            'anggota' => function($q) {
                $q->select('anggota_id', 'nama', 'no_hp');
            },
        ])
        ->where('id', $id)->first();

        $detail = PmbTunaiDetail::where('pmb_tunai_id', $id)->get();

        // return response()->json($data,200);
        return view('pembiayaan::tunai.detail', compact('data', 'detail'));
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pembiayaan::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function riwayat(Request $request)
    {
        if ($request->ajax()) {
            $keyword  = $request->keyword;
            $tgl_mulai = Date::parse($request->tgl_mulai);
            $tgl_akhir = Date::parse($request->tgl_akhir);

            $data = PmbTunaiBayar::
            join('pmb_tunai_detail', 'pmb_tunai_detail.id', '=', 'pmb_tunai_bayar.pmb_tunai_detail_id')
            ->join('pmb_tunai', 'pmb_tunai.id', '=', 'pmb_tunai_detail.pmb_tunai_id')
            ->join('anggota', 'pmb_tunai.anggota_id', '=', 'anggota.anggota_id')
            ->join('admins', 'pmb_tunai_bayar.teller', '=', 'admins.id')
            ->join('anggota as tellers', 'admins.anggota_id', '=', 'tellers.anggota_id')
            ->select('pmb_tunai_bayar.*', 'pmb_tunai_detail.pmb_tunai_id', 'pmb_tunai_detail.angsuran_ke', 'pmb_tunai.no_pembiayaan', 'pmb_tunai.anggota_id', 'anggota.nama as anggota_nama', 'admins.anggota_id', 'tellers.nama as teller_nama')
            // ->whereHas('transaksi_kas', function ($query) use ($keyword) {
            //     return $query->where('akun_id', 14);
            // })
            // ->whereHas('anggota', function ($query) use ($keyword) {
            //     return $query->where('anggota_id', 'like', '%' . $keyword . '%')
            //     ->orWhere('nama', 'like', '%' . $keyword . '%');
            // })
            ->whereBetween('tgl_bayar', [$tgl_mulai, $tgl_akhir])
            ->orderBy('tgl_bayar', 'DESC')
            ->paginate(20);

            return response()->json($data);
        }
        
        return view('pembiayaan::tunai.riwayat');
    }



    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function bayar(Request $request)
    {
        // dd($request->all());
        $rules = [
            'tgl' => 'required',
            'kas_id' => 'required',
            'angsuran_id' => 'required',
        ];

        $pesan = [
            'tgl.required' => 'Tanggal Transaksi Wajib Diisi!',
            'kas_id.required' => 'Kas Wajib Diisi!',
            'angsuran_id.required' => 'Daftar Angsuran Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                

                foreach($request->angsuran_id as $angs){
                    $data = PmbTunaiDetail::where('id', $angs)->first();
                    $data->status = 1;
                    $data->save();

                    $bayar = new PmbTunaiBayar();
                    $bayar->pmb_tunai_detail_id = $angs;
                    $bayar->metode = 'Tunai';
                    $bayar->jumlah = $data->total;
                    $bayar->tgl_bayar = Date::parse($request->tgl)->format('Y-m-d');
                    $bayar->teller = auth()->guard('admin')->user()->id;
                    $bayar->save();

                }

                $pmb_tunai_detail = PmbTunaiDetail::where('pmb_tunai_id', $data->pmb_tunai_id)->where('status', 0)->get();
                if($pmb_tunai_detail->count() == 0){
                    $pmb_tunai = PmbTunai::where('id', $data->pmb_tunai_id)->first();
                    $pmb_tunai->status = 3;
                    $pmb_tunai->save();
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
            ]);
        }
    }
}
