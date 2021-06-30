<?php

namespace Modules\Cabang\Http\Controllers;


use Modules\Cabang\Entities\Cabang;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

class CabangController extends Controller
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
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cabang::where('nama', 'like', '%' . $request->keyword . '%')
            ->orderBy('id', 'ASC')->paginate(10);

            return response()->json($data);
        }
        return view('cabang::index');

    }

    public function save(Request $request)
    {

        $rules = [
            'nama' => 'required',
            'wilayah' => 'required',
            'kode_pos' => 'required',
            'alamat' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Cabang Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'kode_pos.required' => 'Kode POS Wajib Diisi!',
            'alamat.required' => 'Alamat Lengkap Wajib Diisi!',
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

                $data = new Cabang();
                $data->nama = $request->nama;
                $data->wilayah_id = $request->wilayah;
                $data->kode_pos = $request->kode_pos;
                $data->alamat = $request->alamat;
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

    public function update(Request $request)
    {

        $rules = [
            'nama' => 'required',
            'wilayah' => 'required',
            'kode_pos' => 'required',
            'alamat' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Cabang Wajib Diisi!',
            'wilayah.required' => 'Wilayah Wajib Diisi!',
            'kode_pos.required' => 'Kode POS Wajib Diisi!',
            'alamat.required' => 'Alamat Lengkap Wajib Diisi!',
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
                $data = Cabang::find($request->id);
                $data->nama = $request->nama;
                $data->wilayah_id = $request->wilayah;
                $data->kode_pos = $request->kode_pos;
                $data->alamat = $request->alamat;
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

    public function edit($id){
        $data = Cabang::find($id);
        if($data){

            $s = collect([
                'id' => $data->id,
                'nama' => $data->nama,
                'kode_pos' => $data->kode_pos,
                'wilayah_id' => $data->wilayah_id,
                'daerah' => $data->daerah,
                'alamat' => $data->alamat,
            ]);
            return response()->json($s);
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try{
            Cabang::destroy($id);

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
