<?php

namespace Modules\Keuangan\Http\Controllers;

use Modules\Keuangan\Entities\TransaksiKas;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;


class KasTransferController extends Controller
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
            $data = TransaksiKas::with(array(
                'kas' => function($query) {
                    $query->select('id','nama');
                },
                'tujuan' => function($query) {
                    $query->select('id','nama');
                },
                'user' => function($query) {
                    $query->with(['anggota' => function($query) {
                        $query->select('anggota_id','nama');
                    },]);
                    // $query->select('anggota');
                },
                )
            )
            ->where(function($q) use ($keyword){
                $q->where('kd_trans_kas', 'like', '%' . $keyword . '%');
            })
            ->where('jenis', 'transfer')
            ->orderBy('created_at', 'DESC')->paginate(20);

            return response()->json($data);
        }

        return view('keuangan::kas_transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function detail($id)
    {
        $data = TransaksiKas::with(['tujuan', 'kas'])->where('kd_trans_kas',$id)->first();

        return view('keuangan::kas_transfer.detail', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'tgl' => 'required',
            'nominal' => 'required',
            'keterangan' => 'required',
            'tujuan' => 'required',
            'kas_id' => 'required',
        ];

        $pesan = [
            'tgl.required' => 'Tanggal Transfer Kas Wajib Diisi!',
            'nominal.required' => 'Nominal Transfer Kas Wajib Diisi!',
            'keterangan.required' => 'Keterangan Transfer Kas Wajib Diisi!',
            'tujuan.required' => 'Tujuan Kas Transfer Kas Wajib Diisi!',
            'kas_id.required' => 'Dari Kas Wajib Diisi!',
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

                $data = new TransaksiKas();
                $data->kd_trans_kas = get_no_transaksi_kas('transfer');
                $data->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                $data->jumlah = $request->nominal;
                $data->keterangan = $request->keterangan;
                $data->tujuan = $request->tujuan;
                $data->kas_id = $request->kas_id;
                $data->jenis = 'transfer';
                $data->user_id = auth()->guard('admin')->user()->id;
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
        $data = TransaksiKas::with(['tujuan', 'kas'])->where('kd_trans_kas',$id)->first();
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
            'tgl' => 'required',
            'nominal' => 'required',
            'keterangan' => 'required',
            'tujuan' => 'required',
            'kas_id' => 'required',
        ];

        $pesan = [
            'tgl.required' => 'Tanggal Transfer Kas Wajib Diisi!',
            'nominal.required' => 'Nominal Transfer Kas Wajib Diisi!',
            'keterangan.required' => 'Keterangan Transfer Kas Wajib Diisi!',
            'tujuan.required' => 'Tujuan Kas Transfer Kas Wajib Diisi!',
            'kas_id.required' => 'Dari Kas Wajib Diisi!',
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

                $data = TransaksiKas::find($request->id);
                $data->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                $data->jumlah = $request->nominal;
                $data->keterangan = $request->keterangan;
                $data->akun_id = $request->akun_id;
                $data->kas_id = $request->kas_id;
                $data->jenis = 'transfer';
                $data->user_id = auth()->guard('admin')->user()->id;
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
            Kas::destroy($id);

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
