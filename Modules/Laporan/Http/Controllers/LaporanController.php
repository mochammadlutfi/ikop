<?php

namespace Modules\Laporan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Carbon\Carbon;

use Modules\Keuangan\Entities\TransaksiKas;
use Modules\Keuangan\Entities\Kas;
use Modules\Keuangan\Entities\Akun;
use Modules\Keuangan\Entities\AkunKlasifikasi;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function buku_besar(Request $request)
    {
        $kas = Kas ::where('status', 1)->latest()->get();

        return view('laporan::buku_besar', compact('kas'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function simpanan(Request $request)
    {

        if ($request->ajax()) {
            $tgl_mulai = Carbon::parse($request->tgl_mulai)->format('Y-m-d');
            $tgl_akhir = Carbon::parse($request->tgl_akhir)->format('Y-m-d');

            $debit = TransaksiKas::
            whereBetween('tgl', [$tgl_mulai, $tgl_akhir])->
            where('jenis', 'pemasukan')
            ->get();

            $kredit = TransaksiKas::
            whereBetween('tgl', [$tgl_mulai, $tgl_akhir])->
            where('jenis', 'pengeluaran')
            ->get();
            
            $data = Collect([
                [
                    'simpanan' => 'Simpanan Pokok',
                    'debit' => $debit->where('akun_id', 3)->sum('jumlah'),
                    'kredit' => $kredit->where('akun_id', 3)->sum('jumlah'),
                ],
                [
                    'simpanan' => 'Simpanan Wajib',
                    'debit' => $debit->where('akun_id', 4)->sum('jumlah'),
                    'kredit' => $kredit->where('akun_id', 4)->sum('jumlah'),
                ],
                [
                    'simpanan' => 'Simpanan Sosial',
                    'debit' => $debit->where('akun_id', 9)->sum('jumlah'),
                    'kredit' => $kredit->where('akun_id', 9)->sum('jumlah'),
                ],
                [
                    'simpanan' => 'Simpanan Sukarela',
                    'debit' => $debit->where('akun_id', 14)->sum('jumlah'),
                    'kredit' => $kredit->where('akun_id', 14)->sum('jumlah'),
                ],
            ]);
            
            return response()->json($data);
        }
        return view('laporan::simpanan');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function neraca(Request $request)
    {
        $kas = Kas::where('status', 1)->orderBy('nama', 'ASC')->get();
        $klasifikasi = AkunKlasifikasi::where('induk_id', null)->orderBy('kode', 'ASC')->get();

        return view('laporan::neraca', compact('klasifikasi', 'kas'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('laporan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('laporan::edit');
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
