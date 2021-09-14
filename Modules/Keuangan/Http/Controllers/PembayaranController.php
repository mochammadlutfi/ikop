<?php

namespace Modules\Keuangan\Http\Controllers;

use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Entities\Transaksi;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

class PembayaranController extends Controller
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
            if($request->status == 'aktif'){
                $status = array('draft', 'pending');
            }else{
                $status = array('confirm', 'cancel');
            }

            $data = Pembayaran::select('transaksi_bayar.*', 'b.nama as anggota_nama', 'b.anggota_id', 'a.nomor', 'c.logo as bank_logo')
            ->leftJoin('transaksi as a', 'a.id','transaksi_bayar.transaksi_id')
            ->leftJoin('anggota as b', 'b.anggota_id','a.anggota_id')
            ->leftJoin('bank as c', 'c.id','transaksi_bayar.bank_id')
            ->whereIn('transaksi_bayar.status', $status)
            ->orderBy('created_at', 'DESC')->paginate(20);

            return response()->json($data);
        }

        return view('keuangan::payment.aktif');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function detail($id, Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::select('transaksi_bayar.*', 'b.nama as anggota_nama', 'b.anggota_id', 'a.nomor', 'c.logo as bank_logo')
            ->leftJoin('transaksi as a', 'a.id','transaksi_bayar.transaksi_id')
            ->leftJoin('anggota as b', 'b.anggota_id','a.anggota_id')
            ->leftJoin('bank as c', 'c.id','transaksi_bayar.bank_id')
            ->where('transaksi_bayar.id', $id)->firstorfail();

            return response()->json($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function action(Request $request)
    {
        $rules = [
            'id' => 'required',
            'status' => 'required',
        ];

        $pesan = [
            'id.required' => 'Nama Kas Wajib Diisi!',
            'status.required' => 'Simpanan Kas Wajib Diisi!',
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

                $data = Pembayaran::where('id', $request->id)->first();
                $data->status = $request->status;
                $data->save();

                $transaksi = Transaksi::where('id', $data->transaksi_id)->first();
                $transaksi->status = $request->status == 'confirm' ? 1 : 0;
                $transaksi->teller_id = auth()->guard('admin')->user()->id;
                $transaksi->save();

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
        $data = Kas::find($id);
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
            'transfer' => 'required',
            'pengeluaran' => 'required',
            'status' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Kas Wajib Diisi!',
            'simpanan.required' => 'Simpanan Kas Wajib Diisi!',
            'transfer.required' => 'Tranfer Kas Wajib Diisi!',
            'pengeluaran.required' => 'Pengeluaran Kas Wajib Diisi!',
            'status.required' => 'Status Kas Wajib Diisi!',
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

                $data = Kas::find($request->id);
                $data->nama = $request->nama;
                $data->simpanan = $request->simpanan;
                $data->pengeluaran = $request->pengeluaran;
                $data->transfer = $request->transfer;
                $data->status = $request->status;
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function select2(Request $request)
    {
        if(!isset($request->searchTerm)){
            $fetchData = Kas::orderBy('nama', 'ASC')->get();
        }else{
            $cari = $request->searchTerm;
            $fetchData = Kas::where('nama','LIKE',  '%' . $cari .'%')->orderBy('nama', 'ASC')->get();
        }

        $data = array();
        foreach($fetchData as $row) {
            $data[] = array("id" =>$row->id, "text" => $row->nama);
        }

        return response()->json($data);
    }
}
