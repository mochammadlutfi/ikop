<?php

namespace Modules\Anggota\Http\Controllers;


use Modules\Anggota\Entities\Anggota;
use Modules\Anggota\Entities\Alamat;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;

use Modules\Simpanan\Entities\SimkopTransaksi;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Simpanan\Entities\Wallet;

class AnggotaController extends Controller
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
            $data = Anggota::with(["alamat" => function($q){
                $q->where('anggota_alamat.domisili', '=', 1);
            }])
            ->where(function($q) use ($keyword){
                $q->where('anggota_id', 'like', '%' . $keyword . '%')
                  ->orWhere('nama', 'like', '%' . $keyword . '%');
            })
            // ->whereHas('alamat', function ($query) {
            //     return $query->where('domisili', '1');
            // })
            // ->where('nama', 'like', '%' . $request->keyword . '%')
            ->orderBy('anggota_id', 'DESC')->paginate(20);

            return response()->json($data);
        }
        return view('anggota::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('anggota::tambah');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function detail($id)
    {

        $anggota =  Anggota::find($id);
        $alamat = Alamat::where('anggota_id', $anggota->anggota_id)->get();
        return view('anggota::detail', compact('anggota', 'alamat'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getProfile($id)
    {
        $data =  Anggota::where('anggota_id', $id)->first();
        if(!$data){
            return response()->json([
                "fail" => true,
            ], 200);
        }
        
        return response()->json([
            "data" =>  $data,
            "fail" => false,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateProfil(Request $request)
    {
        $rules = [
            'no_ktp' => ['required',
                // 'regex:/^((1[1-9])|(21)|([37][1-6])|(5[1-4])|(6[1-5])|([8-9][1-2]))[0-9]{2}[0-9]{2}(([0-6][0-9])|(7[0-1]))((0[1-9])|(1[0-2]))([0-9]{2})[0-9]{4}$/'
            ],
            'nama' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'jk' => 'required',
            'no_hp' => ['required', 
                // 'regex:/^(^\+62\s?|^0)(\d{3,4}-?){2}\d{3,4}$/'
            ],
            // 'email' => 'email|unique:users',
            'pekerjaan' => 'required',
            'pendidikan' => 'required',
            'status_pernikahan' => 'required',
            'nama_ibu' => 'required',
        ];
        $pesan = [
            'no_ktp.required' => 'No KTP Wajib Diisi!',
            'no_ktp.regex' => 'No KTP Tidak Valid',
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'tmp_lahir.required'  => 'Tempat Lahir Wajib Diisi!',
            'tgl_lahir.required' => 'Tanggal Lahir Wajib Diisi!',
            'jk.required' => 'Jenis Kelamin Wajib Diisi!',
            'no_hp.required' => 'No. HP Wajib Diisi!',
            'no_hp.regex' => 'No. HP Tidak Valid',
            // 'email.required' => 'Alamat Email Wajib Diisi!',
            'pekerjaan.required' => 'Pekerjaan Wajib Diisi!',
            'pendidikan.required' => 'Pendidikan Terakhir Wajib Diisi!',
            'status_pernikahan.required' => 'Status Pernikahan Wajib Diisi!',
            'nama_ibu.required' => 'Nama Orang Tua Wajib Diisi!',
        ];

        $v = Validator::make($request->all(), $rules, $pesan);
        if ($v->fails()) {
            return response()->json([
                'fail' => true,
                'errors' => $v->errors()
            ]);
        }else{
            DB::beginTransaction();

            try{

                $anggota = Anggota::where('anggota_id', $request->anggota_id)->first();
                $anggota->no_ktp = $request->no_ktp;
                $anggota->nama = $request->nama;
                $anggota->jk = $request->jk;
                $anggota->tgl_lahir = Carbon::parse($request->tgl_lahir)->format('Y-m-d');
                $anggota->tmp_lahir = $request->tmp_lahir;
                $anggota->email = $request->email;
                $anggota->no_hp = $request->no_hp;
                $anggota->no_telp = $request->no_telp;
                $anggota->pekerjaan = $request->pekerjaan;
                $anggota->pendidikan = $request->pendidikan;
                $anggota->status_pernikahan = $request->status_pernikahan;
                $anggota->nama_ibu = $request->nama_ibu;
                $anggota->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Alamat',
                ], 404);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ], 200);
        }

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
    public function get_id($id)
    {
        $data =  Anggota::find($id);
        return response()->json($data);
    }

    public function select2(Request $request)
    {
        $cari = $request->searchTerm;
        $fetchData = Anggota::
        where(function($q) use ($cari){
            $q->where('anggota_id', 'like', '%' . $cari . '%')
              ->orWhere('nama', 'like', '%' . $cari . '%');
        })->
        orderBy('anggota_id', 'ASC')->get();

        $data = array();
        foreach($fetchData as $row) {
            $data[] = array("id" =>$row->anggota_id, "text" => $row->anggota_id.' - '.$row->nama);
        }

        return response()->json($data);
    }

    public function coba()
    {
    
        DB::beginTransaction();

            try{
                $anggota_id = 120010036;
                
                $data = Transaksi::with('transaksi_kas')->where(function($q){
                    $q->where('jenis', 'pendaftaran')
                      ->orWhere('jenis', 'setoran wajib')
                      ->orWhere('jenis', 'setoran sukarela')
                      ->orWhere('jenis', 'penarikan sukarela');
                })->where('anggota_id', $anggota_id)->orderBy('tgl_transaksi', 'ASC')->get();

                $wallet = Wallet::where('anggota_id', $anggota_id)->first();
                foreach($data as $a){
                    foreach($a->transaksi_kas as $k)
                    {
                        if($k->keterangan == 'Simpanan Wajib')
                        {
                            $wallet->increment('wajib', $k->jumlah);
                        }else if($k->keterangan == 'Simpanan Sosial')
                        {
                            $wallet->increment('sosial', $k->jumlah);
                        }else if($k->keterangan == 'Simpanan Sukarela')
                        {
                            if($k->jenis == 'pemasukan'){
                                $wallet->increment('sukarela', $k->jumlah);
                            }else{
                                $wallet->decrement('sukarela', $k->jumlah);
                            }
                        }else if($k->keterangan == 'Simpanan Pokok')
                        {
                            $wallet->increment('pokok', $k->jumlah);
                        }
                    }
                }
            
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Alamat',
                ]);
            }

        // $wallet = Wallet::where('anggota_id', '120010001')->first();
        // $wallet->increment('wajib', 100000);
        // $wallet->increment('sosial', 5000);

        DB::commit();
        dd('beres');

    }
    
}
