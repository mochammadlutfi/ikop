<?php

namespace App\Http\Controllers;

use Modules\Keuangan\Entities\Pembayaran;
use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiLine;
use Modules\Anggota\Entities\Anggota;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

use Date;

use App\Models\User;
use App\Helpers\Notification;

class TransaksiController extends Controller
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
    public function aktif(Request $request)
    {

        if ($request->ajax()) {
            $status = array('draft', 'pending');

            $data = Transaksi::select('transaksi.id', 'transaksi.anggota_id', 'transaksi.total', 'transaksi.service', 'transaksi.sub_service', 'transaksi.jenis', 'transaksi.tgl', 'transaksi.status','transaksi.nomor',)
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'transaksi.anggota_id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
                $q->with(['bank:id,logo']);
            }, 'anggota'=> function($q){
                $q->select(['anggota_id', 'nama']);
            },])
            ->where('transaksi.status', 0)
            ->orderBy('transaksi.tgl', 'DESC')
            ->paginate(20);
            return response()->json($data);
        }

        return view('transaksi.list_aktif');
    }

    public function selesai(Request $request)
    {

        if ($request->ajax()) {
            $keyword  = $request->keyword;
            $tgl_mulai = Date::parse($request->tgl_mulai);
            $tgl_akhir = Date::parse($request->tgl_akhir);
            $status = array('confirm', 'cancel');

            $data = Transaksi::select('transaksi.id', 'transaksi.anggota_id', 'transaksi.total', 'transaksi.service', 'transaksi.sub_service', 'transaksi.jenis', 'transaksi.tgl', 'transaksi.status','transaksi.nomor',)
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'transaksi.anggota_id')
            ->with(['pembayaran' => function($q){
                $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
                $q->with(['bank:id,logo']);
            }, 'anggota'=> function($q){
                $q->select(['anggota_id', 'nama']);
            },])
            ->where('transaksi.status', 1)
            ->orderBy('transaksi.tgl', 'DESC')
            ->paginate(20);

            return response()->json($data);
        }

        return view('transaksi.list_selesai');
    }

    public function detail($id){

        $data = Transaksi::where('id', $id)->firstOrfail();
        $line = TransaksiLine::where('transaksi_id', $id)->get();

        return view('transaksi.detail', compact('data', 'line'));
    }


    public function countAktif(){

        $data = Transaksi::select('transaksi.id', 'transaksi.anggota_id', 'transaksi.total', 'transaksi.service', 'transaksi.sub_service', 'transaksi.jenis', 'transaksi.tgl', 'transaksi.status','transaksi.nomor',)
        ->leftJoin('anggota as a', 'a.anggota_id', '=', 'transaksi.anggota_id')
        ->with(['pembayaran' => function($q){
            $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
            $q->with(['bank:id,logo']);
        }, 'anggota'=> function($q){
            $q->select(['anggota_id', 'nama']);
        },])
        ->where('transaksi.status', 0)
        ->orderBy('transaksi.tgl', 'DESC')->get()->count();

        return response()->json($data);
    }
}
