<?php

namespace Modules\Keuangan\Http\Controllers;

use Modules\Keuangan\Entities\Akun;
use Modules\Keuangan\Entities\AkunKlasifikasi;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

class AkunController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard
     * are allowed.
     *
     * @return void
     */
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

        if ($request->ajax()) {
            $keyword  = $request->keyword;
            $data = Akun::with(["klasifikasi" => function($q) use($keyword){
                $q->where('nama', 'like', '%' . $keyword . '%');
            }])->where('nama', 'like', '%' . $keyword . '%')
            ->orderBy('kode', 'ASC')->paginate(20);

            return response()->json($data);
        }

        return view('keuangan::akun.index');
    }
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'simpanan' => 'required',
            'kode' => 'required',
            'pengeluaran' => 'required',
            'klasifikasi_id' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Akun Wajib Diisi!',
            'simpanan.required' => 'Simpanan Akun Wajib Diisi!',
            'kode.required' => 'Kode Akun Wajib Diisi!',
            'pengeluaran.required' => 'Pengeluaran Akun Wajib Diisi!',
            'klasifikasi_id.required' => 'Klasifikasi Akun Wajib Diisi!',
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

                $data = new Akun();
                $data->kode = $request->kode;
                $data->nama = $request->nama;
                $data->pemasukan = $request->simpanan;
                $data->pengeluaran = $request->pengeluaran;
                $data->klasifikasi_id = $request->klasifikasi_id;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Akun::with('klasifikasi')->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'simpanan' => 'required',
            'kode' => 'required',
            'pengeluaran' => 'required',
            'klasifikasi_id' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Akun Wajib Diisi!',
            'simpanan.required' => 'Simpanan Akun Wajib Diisi!',
            'kode.required' => 'Kode Akun Wajib Diisi!',
            'pengeluaran.required' => 'Pengeluaran Akun Wajib Diisi!',
            'klasifikasi_id.required' => 'Klasifikasi Akun Wajib Diisi!',
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

                $data = Akun::find($request->id);
                $data->kode = $request->kode;
                $data->nama = $request->nama;
                $data->pemasukan = $request->simpanan;
                $data->pengeluaran = $request->pengeluaran;
                $data->klasifikasi_id = $request->klasifikasi_id;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete ($id)
    {
        DB::beginTransaction();
        try{
            Akun::destroy($id);

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e,
            ]);
        }
        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function select2(Request $request)
    {
        if(!isset($request->searchTerm)){
            $fetchData = AkunKlasifikasi::where('induk_id', NULL)->orderBy('nama', 'ASC')->get();
        }else{
            $cari = $request->searchTerm;
            $fetchData = AkunKlasifikasi::where('induk_id', NULL)->where('title','LIKE',  '%' . $cari .'%')->orderBy('nama', 'ASC')->get();
        }

        $data = array();
        foreach($fetchData as $row) {
            $data[] = array("id" =>$row->id, "text" => $row->nama);
            foreach($row->child as $child){
                if($row->id == $child->induk_id){
                    $data[] = array("id" => $child->id, "text" => $child->kode.' - '.$child->nama);
                }

                foreach($child->akun as $akun){
                    if($child->id == $akun->klasifikasi_id){
                        $data[] = array("id" => $akun->id, "text" => $akun->kode.' - '.$akun->nama);
                    }
                }

            }

        }

        return response()->json($data);
    }
}
