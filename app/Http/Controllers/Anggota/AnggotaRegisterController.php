<?php

namespace Modules\Anggota\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Storage;
use Carbon\Carbon;


use App\Models\Anggota;
use App\Models\Alamat;
use App\Models\Kelurahan;

use App\Models\Transaksi;
use App\Models\TransaksiKas;

use App\Models\SimkopTransaksi;
use App\Models\SimlaTransaksi;
use App\Models\Wallet;
class AnggotaRegisterController extends Controller
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
     * Menampilkan Form 1
     * 
     * @return Renderable
     */
    public function step1(Request $request)
    {
        $anggota = $request->session()->get('newAnggota');

        return view('anggota::register.step1', compact('anggota'));
    }

     /**
     * Menyimpan data form 1 ke dalam session
     * 
     * @return Renderable
     */
    public function step1Store(Request $request)
    {

        if(empty($request->session()->get('newAnggota'))){
            // if(!empty($request->featured_img))
            // {
            //     $image_parts = explode(";base64,", $request->featured_img);
            //     $image_type_aux = explode("image/", $image_parts[0]);
            //     $image_type = $image_type_aux[1];
            //     $thumb = base64_decode($image_parts[1]);
            //     $thumbName = 'foto-'.uniqid() .'.'.$image_type;
            //     $thumbPath = 'temp/anggota/' . $thumbName;
            //     $p = Storage::disk('umum')->put($thumbPath, $thumb);
            //     $request->foto = 'uploads/'.$thumbPath;
            // }

            // if(!empty($request->featured_img))
            // {
            //     $image_parts = explode(";base64,", $request->featured_img);
            //     $image_type_aux = explode("image/", $image_parts[0]);
            //     $image_type = $image_type_aux[1];
            //     $thumb = base64_decode($image_parts[1]);
            //     $thumbName = 'ktp-'.uniqid() .'.'.$image_type;
            //     $thumbPath = 'temp/anggota/' . $thumbName;
            //     $p = Storage::disk('umum')->put($thumbPath, $thumb);
            //     $request->foto = 'uploads/'.$thumbPath;
            // }
            
            // $anggota = new Anggota();
            $request->session()->forget('newAnggota');
            $anggota = collect($request->all());
            $request->session()->put('newAnggota', $anggota);
        }else{
            $anggota = $request->session()->get('newAnggota');
            $anggota = collect($request->all());
            $request->session()->put('newAnggota', $anggota);
        }
        
        return response()->json([
            'fail' => false,
        ]);
    }

    /**
     * Menampilkan Form 2
     * 
     * @return Renderable
     */
    public function step2(Request $request)
    {
        $anggota = $request->session()->get('newAnggota');
        $alamat = $request->session()->get('newAnggotaAlamat');
        return view('anggota::register.step2', compact('anggota', 'alamat'));
    }

     /**
     * Menyimpan data form 2 ke dalam session
     * 
     * @return Renderable
     */
    public function step2Store(Request $request)
    {

        // dd($request->all());
        // $request->session()->forget('newAnggotaAlamat');
        $wilayah1 = Kelurahan::find($request->wilayah_id);

        $alamat[0] = array(
                'domisili' => $request->domisili,
                'wilayah_id' => $request->wilayah_id,
                'wilayah_text' => $wilayah1->daerah,
                'alamat' => $request->alamat,
                'pos' => $request->kode_pos,
            );
        if(!empty($request->alamat2)){
            
            $wilayah2 = Kelurahan::find($request->wilayah_id2);
            // dd($wilayah2);
            $alamat[1] = array(
                'domisili' => $request->domisili2,
                'wilayah_id' => $request->wilayah_id2,
                'wilayah_text' => $wilayah2->daerah,
                'alamat' => $request->alamat2,
                'pos' => $request->kode_pos2,
            );
        }

        if(empty($request->session()->get('newAnggotaAlamat'))){
            $request->session()->forget('newAnggotaAlamat');
            $request->session()->put('newAnggotaAlamat', $alamat);
        }else{
            $request->session()->forget('newAnggotaAlamat');
            $request->session()->put('newAnggotaAlamat', $alamat);
        }
        
        return response()->json([
            'fail' => false,
        ]);
    }

    /**
     * Menampilkan Form 3
     * 
     * @return Renderable
     */
    public function step3(Request $request)
    {
        $anggota = $request->session()->get('newAnggota');

        return view('anggota::register.step3', compact('anggota'));
    }

     /**
     * Menyimpan data form 3 ke dalam session
     * 
     * @return Renderable
     */
    public function step3Store(Request $request)
    {
        
        $rules = [
        ];
        $pesan = [
        ];

        $v = Validator::make($request->all(), $rules, $pesan);
        if ($v->fails()) {
            return response()->json([
                'fail' => true,
                'errors' => $v->errors()
            ]);
        }else{
            // dd($request->all());
            DB::beginTransaction();

            try{
                $newAnggota = $request->session()->get('newAnggota');
                $anggota_id = $newAnggota['anggota_id'];
                // $anggota_id = '120010022';

                $anggota = new Anggota();
                $anggota->anggota_id =  $anggota_id;
                $anggota->no_ktp = $newAnggota['no_ktp'];
                $anggota->nama = $newAnggota['nama'];
                $anggota->jk = $newAnggota['jk'];
                $anggota->tgl_lahir = Carbon::parse($newAnggota['tgl_lahir'])->format('Y-m-d');
                $anggota->tmp_lahir = $newAnggota['tmp_lahir'];
                $anggota->email = $newAnggota['email'];
                $anggota->no_hp = $newAnggota['no_hp'];
                $anggota->no_telp = $newAnggota['no_telp'];
                $anggota->pekerjaan = $newAnggota['pekerjaan'];
                $anggota->pendidikan = $newAnggota['pendidikan'];
                $anggota->status_pernikahan = $newAnggota['status_pernikahan'];
                $anggota->nama_ibu = $newAnggota['nama_ibu'];
                if($request->hasfile('ktp')){
                    $anggota->ktp = $ktp;
                }
                if($request->hasfile('foto')){
                    $anggota->foto = $foto;
                }
                $anggota->cabang_id = 1;
                $anggota->status = 1;
                $anggota->tgl_gabung = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                $anggota->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                $anggota->save();

                $alamat = array();
                foreach($request->session()->get('newAnggotaAlamat') as $a){
                    $alamat[] = array(
                        'anggota_id' => $anggota_id,
                        'alamat' => $a['alamat'],
                        'domisili' => $a['domisili'],
                        'wilayah_id' => $a['wilayah_id'],
                        'pos' => $a['pos'],
                    );
                }
                Alamat::insert($alamat);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Alamat',
                ]);
            }


            try{
                $program = array(
                    array(
                        'keterangan' => 'Administrasi',
                        'nominal' => 25000,
                        'akun' => 12,
                    ),
                    array(
                        'keterangan' => 'Simpanan Pokok',
                        'nominal' => 200000,
                        'akun' => 3,
                    ),
                    array(
                        'keterangan' => 'Simpanan Wajib',
                        'nominal' => 100000,
                        'akun' => 4,
                    ),
                    array(
                        'keterangan' => 'Simpanan Sosial',
                        'nominal' => 5000,
                        'akun' => 9,
                    )
                );
                $total = 330000;

                if(!empty($request->simla)){
                    $simla = array(
                        'keterangan' => 'Simpanan Sukarela',
                        'nominal' => $request->simla,
                        'akun' => 14,
                    );

                    $total += $request->simla;

                    array_push($program, $simla);
                }

                $no_transaksi = $request->no_invoice;

                $transaksi = new Transaksi();
                $transaksi->no_transaksi = $no_transaksi;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->teller_id  = auth()->user()->id;
                $transaksi->item = json_encode($program);
                $transaksi->total = $total;
                $transaksi->tgl_transaksi = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                $transaksi->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                $transaksi->save();

                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->no_transaksi = $no_transaksi;
                $pay_wajib->anggota_id  = $anggota_id;
                $pay_wajib->jumlah = 100000;
                $pay_wajib->periode = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                $pay_wajib->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                $pay_wajib->save();
                
                $wallet = new Wallet();
                $wallet->anggota_id = $anggota_id;
                $wallet->wajib = 100000;
                $wallet->pokok = 200000;
                $wallet->sosial = 5000;
                $wallet->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');

                if(!empty($request->simla))
                {
                    $simla = new SimlaTransaksi();
                    $simla->no_transaksi = $no_transaksi;
                    $simla->anggota_id  = $anggota_id;
                    $simla->type = 'deposit';
                    $simla->amount = $request->simla;
                    $simla->tgl = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                    $simla->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                    $simla->save();

                    $wallet->sukarela = $request->simla;
                }
                
                $wallet->save();

                foreach($program as $p)
                {
                    $kas = new TransaksiKas();
                    $kas->kd_trans_kas = get_no_transaksi_kas('pemasukan');
                    $kas->no_transaksi = $no_transaksi;
                    $kas->kas_id = 1;
                    $kas->jumlah = $p['nominal'];
                    $kas->keterangan = $p['keterangan'];
                    $kas->jenis = 'pemasukan';
                    $kas->akun_id = $p['akun'];
                    $kas->user_id = auth()->guard('admin')->user()->id;
                    $kas->tgl = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                    $kas->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                    $kas->save();
                }

                // $adm = new TransaksiKas();
                // $adm->kd_trans_kas = get_no_transaksi_kas('pemasukan');
                // $adm->no_transaksi = $no_transaksi;
                // $adm->kas_id = 1;
                // $adm->jumlah = 25000;
                // $adm->keterangan = 'Administrasi';
                // $adm->jenis = 'pemasukan';
                // $adm->akun_id = 12;
                // $adm->user_id = auth()->user()->id;
                // $adm->tgl = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                // $adm->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                // $adm->save();

                // $pokok = new TransaksiKas();
                // $pokok->kd_trans_kas = get_no_transaksi_kas('pemasukan');
                // $pokok->no_transaksi = $no_transaksi;
                // $pokok->kas_id = 1;
                // $pokok->jumlah = 200000;
                // $pokok->keterangan = 'Simpanan Pokok';
                // $pokok->jenis = 'pemasukan';
                // $pokok->akun_id = 3;
                // $pokok->user_id = auth()->user()->id;
                // $pokok->tgl = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                // $pokok->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                // $pokok->save();

                // //Simpanan Wajib
                // $kas_wajib = new TransaksiKas();
                // $kas_wajib->kd_trans_kas = get_no_transaksi_kas('pemasukan');
                // $kas_wajib->no_transaksi = $no_transaksi;
                // $kas_wajib->kas_id = 1;
                // $kas_wajib->jumlah = 100000;
                // $kas_wajib->keterangan = 'Simpanan Wajib';
                // $kas_wajib->jenis = 'pemasukan';
                // $kas_wajib->akun_id = 4;
                // $kas_wajib->user_id = auth()->user()->id;
                // $kas_wajib->tgl = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                // $kas_wajib->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                // $kas_wajib->save();

                // $kas_sosial = new TransaksiKas();
                // $kas_sosial->kd_trans_kas = get_no_transaksi_kas('pemasukan');
                // $kas_sosial->no_transaksi = $no_transaksi;
                // $kas_sosial->kas_id = 1;
                // $kas_sosial->jumlah = 5000;
                // $kas_sosial->keterangan = 'Simpanan Sosial';
                // $kas_sosial->jenis = 'pemasukan';
                // $kas_sosial->akun_id = 9;
                // $kas_sosial->user_id = auth()->guard('admin')->user()->id;
                // $kas_sosial->tgl = Carbon::parse($request->tgl_payment)->format('Y-m-d');
                // $kas_sosial->created_at = Carbon::parse($request->tgl_payment)->format('Y-m-d H:i:s');
                // $kas_sosial->save();

                
                
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Menyimpan Data Transaksi',
                    'log' => $e,
                ]);
            }

            
            $request->session()->forget('newAnggota');
            $request->session()->forget('newAnggotaAlamat');


            DB::commit();
            return response()->json([
                'fail' => false,
                'invoice' => $no_transaksi,
            ]);
        }
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
        // dd($anggota->alamat());
        return view('anggota::detail', compact('anggota', 'alamat'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('anggota::edit');
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
}
