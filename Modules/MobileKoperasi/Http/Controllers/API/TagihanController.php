<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Anggota\Entities\Anggota;
use Modules\Simpanan\Entities\Wallet;
use Modules\Simpanan\Entities\SimkopTransaksi;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Date;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

class TagihanController extends Controller
{

    public function simpanan(Request $request){

        $anggota_id = $request->user()->anggota_id;
        $tgl_gabung = $request->user()->anggota->tgl_gabung;
        $data = collect([]);

        $data = $data->merge([$this->simkop($tgl_gabung, $anggota_id)],);

        return response()->json([
            'data' => $data,
            'fail' => false,
        ], 200);
    }

    private function simkop($tgl_gabung, $anggota_id){
        $dari = Date::parse($tgl_gabung)->startOfMonth()->year('2021');
        $now = Date::now()->endOfMonth();
        $diff_in_months = $dari->diffInMonths($now);

        $nominal = 0;
        $jumlah = 0;
        $list = collect([]);
        for($i = 0; $i <= $diff_in_months; $i++)
        {
            $bulan = SimkopTransaksi::where('anggota_id', $anggota_id)
            ->whereMonth('periode', $dari->format('m'))
            ->whereYear('periode', $dari->format('Y'))
            ->first();
            if(!$bulan)
            {
                $nominal += 100000;
                $jumlah += 1;
                $list->push($dari->format('d-m-Y'));
            }
            $dari->addMonth(1);
        }

        // $data = $d->sortBy('product_price')->values();
        // $sorted = $list->sort()->values()->all();

        $data = collect([
            'service' => 'Simpanan Wajib',
            'subService' => 'wajib',
            'jumlah' => $jumlah,
            'nominal' => (int)$nominal,
            'list' => $list,
        ]);

        return $data;
    }

    public function detail($slug, Request $request)
    {
        if($slug == 'wajib'){
            $anggota_id = $request->user()->anggota_id;
            $tgl_gabung = $request->user()->anggota->tgl_gabung;

            $dari = Date::parse($tgl_gabung)->startOfMonth()->year('2021');
            $now = Date::now()->endOfMonth();
            $diff_in_months = $dari->diffInMonths($now);

            $nominal = 0;
            $jumlah = 0;
            $list = collect([]);
            for($i = 0; $i <= $diff_in_months; $i++)
            {
                $bulan = SimkopTransaksi::where('anggota_id', $anggota_id)
                ->whereMonth('periode', $dari->format('m'))
                ->whereYear('periode', $dari->format('Y'))
                ->first();
                if(!$bulan)
                {
                    $nominal += 100000;
                    $jumlah += 1;
                    $list->push($dari->format('F Y'));
                }
                $dari->addMonth(1);
            }

            $data = collect([
                'service' => 'Simpanan Wajib',
                'subService' => 'wajib',
                'jumlah' => $jumlah,
                'nominal' => (int)$nominal,
                'list' => $list,
            ]);
        }

        return response()->json([
            'data' => $data,
            'fail' => false,
        ], 200); 
    }
    

}
