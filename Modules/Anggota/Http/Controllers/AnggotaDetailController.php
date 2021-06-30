<?php

namespace Modules\Anggota\Http\Controllers;


use Modules\Anggota\Entities\Anggota;
use Modules\Anggota\Entities\Alamat;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

use Modules\Keuangan\Entities\Transaksi;
use Modules\Simpanan\Entities\Wallet;
use Carbon\Carbon;

class AnggotaDetailController extends Controller
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
    public function index(Request $request, $id)
    {
        
        $anggota =  Anggota::find($id);
        $alamat = Alamat::where('anggota_id', $anggota->anggota_id)->get();

        return view('anggota::detail', compact('anggota', 'alamat'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function simpanan( $id, Request $request)
    {
        if ($request->ajax()) {

            $wallet = Wallet::where('anggota_id', $id)->first();
            $simpanan = collect([
                [
                    'program' => 'Simpanan Pokok',
                    'saldo' => $wallet->pokok,
                    'akun' => 3,
                ],
                [
                    'program' => 'Simpanan Wajib',
                    'saldo' => $wallet->wajib,
                    'akun' => 4,
                ],
                [
                    'program' => 'Simpanan Sosial',
                    'saldo' => $wallet->sosial,
                    'akun' => 9,
                ],
                [
                    'program' => 'Simpanan Sukarela',
                    'saldo' => $wallet->sukarela,
                    'akun' => 14,
                ],
            ]);
            
            $riwayat = Transaksi::with([
                'teller' => function($query) {
                    $query->with(['anggota' => function($query) {
                        $query->select('anggota_id','nama');
                    }]);
                },
                'transaksi_kas' => function($query){

                }
            ])
            ->where('anggota_id', $id)
            ->orderBy('tgl_transaksi', 'DESC')
            ->paginate(20);

            $data = collect([
                'simpanan' => $simpanan,
                'riwayat' => $riwayat,
            ]);

            return response()->json($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function transaksi($id, Request $request)
    {
        if ($request->ajax()) {
            if(!empty($request->start_date) && !empty($request->end_date)){
                $start = Carbon::parse($request->start_date);
                $end = Carbon::parse($request->end_date);
            }else{
                $start = Carbon::now()->subDays(30);
                $end = Carbon::now();
            }
            $keyword  = $request->keyword;

            $data = Transaksi::
            with([
            'teller' => function($query) {
                $query->with(['anggota' => function($query) {
                    $query->select('anggota_id','nama');
                }]);
            },
            ])
            ->where('anggota_id', $id)
            ->orderBy('tgl_transaksi', 'DESC')
            ->paginate(20);

            return response()->json($data);
        }
    }
    
}
