<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Anggota\Entities\Anggota;
use App\Models\Slider;
use Modules\Simpanan\Entities\SimkopTransaksi;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Date;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{

    public function index(Request $request){

        $anggota_id = $request->user()->anggota_id;
        $tgl_gabung = $request->user()->anggota->tgl_gabung;

        $response = Slider::where('is_active', 1)->get();
        
        $response->each(function ($data) {
            $data->img_url = 'http://192.168.1.2/bumaba/public/'.$data->img;
        });

        return response()->json([
            'data' => $response,
            'fail' => false,
        ], 200);
    }

}
