<?php

namespace Modules\Pengguna\Http\Controllers;


use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

class PenggunaController extends Controller
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

            $keyword = $request->keyword;

            $data = Admin::with([
                'anggota' => function($query) use ($keyword) {
                    $query->select('anggota_id','nama', 'no_ktp', 'no_hp');
                    $query->where('nama', 'like', '%' . $keyword . '%');
                },
                'roles'
            ])
            ->orderBy('id', 'ASC')->paginate(10);

            return response()->json($data);
        }
        $role = Role::latest()->get();

        return view('pengguna::index', compact('role'));

    }

    public function store(Request $request)
    {

        $rules = [
            'anggota_id' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
            'password.required' => 'Password Wajib Diisi!',
            'role.required' => 'Jabatan Wajib Diisi!',
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

                $data = new Admin();
                $data->anggota_id = $request->anggota_id;
                $data->username = $request->username;
                $data->password = bcrypt($request->password);
                $data->save();
 
                $data->assignRole($request->role);

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
        $data = Admin::where('id', $id)->first();
        if($data){

            $s = collect([
                'id' => $data->id,
                'anggota_id' => $data->anggota_id,
                'username' => $data->username,
                'anggota_detail' => $data->anggota_id .' - '. $data->anggota->nama,
                'role' => $data->roles->first()->id,
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
