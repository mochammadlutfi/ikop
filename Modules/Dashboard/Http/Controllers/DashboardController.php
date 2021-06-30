<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Keuangan\Entities\TransaksiKas;

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
    public function index()
    {

        // $pemasukan = TransaksiKas::whereBetween('created_at', [$start, $now])->where('jenis', 'pemasukan')
        //     ->groupBy('date')
        //     ->orderBy('date')
        //     ->get( [
        //         DB::raw( 'DAY(created_at) as date' ),
        //         DB::raw( 'COUNT(*) as "count"' )
        //     ] )
        //     ->pluck('count', 'date');
        // $pengeluaran = TransaksiKas::whereBetween('created_at', [$start, $now])->where('jenis', 'pengeluaran')
        //     ->groupBy('date')
        //     ->orderBy('date')
        //     ->get( [
        //         DB::raw( 'DAY(created_at) as date' ),
        //         DB::raw( 'COUNT(*) as "count"' )
        //     ] )
        //     ->pluck('count', 'date');
        // for($i = 1; $i <= $days ; $i++)
        // {
        //     $tgl[] = $this->get_hari($start3->addDays()->format('Y-m-d')).' - '.$start->addDays()->format('d/m');
        //     $dateString = ltrim($start2->addDays()->format('d'), "0");
        //     if(isset($pemasukan[$dateString])) {
        //         $masuk[] = $pemasukan[$dateString];
        //     }else{
        //         $masuk[] = 0;
        //     }
        //     if(isset($pengeluaran[$dateString])) {
        //         $keluar[] = $pengeluaran[$dateString];
        //     }else{
        //         $keluar[] = 0;
        //     }
        // }

        // $chart_masuk = new PemasukanKasChart;
        // $chart_masuk->labels($tgl)->displayAxes('yAxes');
        // $chart_masuk->dataset('Pemasukan Kas', 'line', $masuk)
        // ->backgroundColor('rgba(66,165,245,.25)')
        // ->color('rgba(66,165,245,1)')
        // ->fill(TRUE);
        // $chart_masuk->dataset('Pengeluaran Kas', 'line', $keluar)
        // ->backgroundColor('rgba(156,204,101,.45)')
        // ->color('rgba(156,204,101,1)')
        // ->fill(TRUE);

        return view('dashboard::index');
    }
}
