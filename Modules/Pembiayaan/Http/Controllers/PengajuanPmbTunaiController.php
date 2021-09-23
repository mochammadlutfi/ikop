<?php

namespace Modules\Pembiayaan\Http\Controllers;

use Modules\Pembiayaan\Entities\PmbTunai;
use Modules\Pembiayaan\Entities\PmbTunaiDetail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Date;
class PengajuanPmbTunaiController extends Controller
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
        return view('pembiayaan::tunai.pengajuan_list');
    }


    public function data(Request $request){
        if ($request->ajax()) {
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
            ->where('status', "pending")
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

            return response()->json($data, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pembiayaan::tunai.pengajuan_form');
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
        

        for($i=1; $i <= $data->durasi; $i++ ){
            $line[] = [
                'no' => $i,
                'angsuran_pokok' => $data->jumlah / $data->durasi,
                'angsuran_bunga' => $data->jumlah_bunga / $data->durasi,
                'jumlah_angsuran' => ($data->jumlah/2) + $data->jumlah_bunga / $data->durasi,
                'tempo' => Date::parse($data->created_at)->addMonth($i)->day(10)->format('Y-m-d'),
            ];
        }

        $data->line = $line;

        return response()->json($data,200);
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
    public function destroy($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function action($id, Request $request)
    {
        $status = $request->status;
        DB::beginTransaction();
        try{
            $data = PmbTunai::where('id', $id)->first();
            $data->status = $status;
            $data->save();

            if($status == 1){

                for($i = 1; $i <= $data->durasi; $i++){
                    $detail = new PmbTunaiDetail();
                    $detail->pmb_tunai_id = $data->id;
                    $detail->angsuran_ke = $i;
                    $detail->jumlah_pokok = $data->jumlah / $data->durasi;
                    $detail->jumlah_bunga = $data->jumlah_bunga / $data->durasi;
                    $detail->total = ($data->jumlah/2) + $data->jumlah_bunga / $data->durasi;
                    $detail->status = 0;
                    $detail->tgl_tempo = Date::parse($data->created_at)->addMonth($i)->day(10)->format('Y-m-d');
                    $detail->save();
                }
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
