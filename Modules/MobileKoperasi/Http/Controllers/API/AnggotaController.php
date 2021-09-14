<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Anggota\Entities\Anggota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use DB;
use Date;
class AnggotaController extends Controller
{
    
     /**
     * Get Saldo Simla.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detail(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;

        $phone = preg_replace('/^\+?62|\|62|\D/', '', ($request->phone));

        $data = Anggota::where('no_hp', $phone)->first();
        if($data){
            return response()->json([
                'data' => $data,
                'fail' => false,
            ], 200);
        }else{
            return response()->json([
                'message' => "No Hp Tidak Ditemukan",
                'fail' => false,
            ], 400);
        }
        
    }


}
