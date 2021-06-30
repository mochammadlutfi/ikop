<?php

namespace Modules\Cabang\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Cabang\Entities\Kelurahan;

class WilayahController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jsonSelect(Request $request)
    {
        if($request->has('searchTerm')){
            $cari = $request->searchTerm;
                $fetchData = Kelurahan::where('name','LIKE',  '%' . $cari .'%')->get();
            $data = array();
            // dd($fetchData);
            foreach($fetchData as $row) {
                $data[] = array(
                    "id" =>$row->id,
                    "text" => ucwords(strtolower($row->kecamatan->kota->provinsi->name)).', '. ucwords(strtolower($row->kecamatan->kota->name)).', Kec. '.ucwords(strtolower($row->kecamatan->name)).', '.ucwords(strtolower($row->name)),
                );
            }
            return response()->json($data);
        }
    }
}
