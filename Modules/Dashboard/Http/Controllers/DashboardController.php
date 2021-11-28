<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Keuangan\Entities\TransaksiLine;
use Modules\Keuangan\Entities\Transaksi;
use Carbon\Carbon;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $requsest)
    {
        return view('dashboard::index');
    }

    public function data(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return response()->json([
            'simpanan' => $this->hitungSimpanan($start_date, $end_date),
            'kas' => $this->hitungKas($start_date, $end_date),
            'chartTransaksi' => $this->chartTransaksi(),
        ]);
    }

    private function hitungSimpanan($start, $end){
        $simpananPemasukan = TransaksiLine::whereIn('akun_id', array(3, 4, 14,9))
        ->whereBetween('created_at', [$start, $end])
        ->where('jenis', 'pemasukan')->sum('jumlah');

        
        $simpananPengeluaran = TransaksiLine::whereIn('akun_id', array(3, 4, 14, 9))
        ->whereBetween('created_at', [$start, $end])
        ->where('jenis', 'pengeluaran')->sum('jumlah');

        $simpananResult = collect([
            'Pemasukan' => $simpananPemasukan,
            'Pengeluaran' => $simpananPengeluaran,
            'Total' => $simpananPemasukan - $simpananPengeluaran,
        ]);
        return $simpananResult;
    }

    private function hitungKas($start, $end)
    {
        $today = Carbon::now();

        $simpananPemasukan = TransaksiLine::whereNotIn('akun_id', array(3, 4, 14,9))
        ->whereBetween('tgl', [$start, $end])
        ->where('jenis', 'pemasukan')->sum('jumlah');
        
        $simpananPengeluaran = TransaksiLine::whereNotIn('akun_id', array(3, 4, 14, 9))
        ->whereBetween('tgl', [$start, $end])
        ->where('jenis', 'pengeluaran')->sum('jumlah');

        $simpananResult = collect([
            'Pemasukan' => $simpananPemasukan,
            'Pengeluaran' => $simpananPengeluaran,
            'Total' => $simpananPemasukan - $simpananPengeluaran,
        ]);

        return $simpananResult;
    }


    private function chartTransaksi(){
        $data = Transaksi::where('created_at', '>=', Carbon::now()->subMonth())
        ->groupBy('date')
        ->orderBy('date', 'DESC')
        ->get(array(
            DB::raw('Date(created_at) as date'),
            DB::raw('COUNT(*) as "transaksi"')
        ));
        $label = array();
        foreach($data as $d){
            $label[] = $d->date;
            $val[] = $d->transaksi;
        }
        $resp = Collect([
            "label" => $label,
            "data" => $val,
        ]);

        return $resp;
    }

}
