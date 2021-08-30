<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Modules\Keuangan\Entities\Payment;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Bank\Entities\Bank;
use Date;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use DB;
use GuzzleHttp\Client;


class PPOBController extends Controller
{

    private $apikey;
    private $username;
    private $client;
    public function __construct()
    {
        $this->client = new Client();
        $this->username = "089656466525";
        $this->apikey = "3965e5b9f739b3ac";
    }

    
    public function index(Request $request){
        $body = [
            "commands" => "pricelist",
            "username" => $this->username,
            "sign"     => md5($this->username.$this->apikey.'pl'),
            "status"   => "active"
        ];
        $json = '{
                "commands" : "pricelist",
                "username" : "089656466525",
                "sign"     : "841413dc0a2c800c45f63d7faa1bfbdb",
                "status"   : "active"
                }';

        $url = "https://testprepaid.mobilepulsa.net/v1/legacy/index/" .$request->type. "/".$request->operator;
        $client = new Client();
        $req = $client->request('POST', $url, [
            'headers' => [ 'Content-Type' => 'application/json' ],
            'body' => json_encode($body)
		]);

        $response = json_decode($req->getBody());
        // dd($response);
        if($request->type == 'pulsa'){
            $data = $this->pulsa($response);
        }else if($request->type == 'data'){
            $data =  $this->data($response);
        }else{
            $data = $response;
        }
        
        return response()->json([
            'data' => $data,
            'fail'=> false
        ],200);
    }


    protected function pulsa($data){
        if($data){
            $response = collect();
            foreach($data->data as $d){
                if(is_numeric($d->pulsa_nominal))
                $response->push([
                    'code' => $d->pulsa_code,
                    'nama' => $d->pulsa_op.' '.$d->pulsa_nominal,
                    'harga' => $d->pulsa_price,
                    'details' => 'Masa Aktif '.$d->masaaktif. ' hari',
                ]);
            }
            $sorted = $response->sortBy('harga')->values();
            return $sorted;
        }
    }

    protected function data($data){
        if($data){
            $response = collect();
            foreach($data->data as $d){
                $response->push([
                    'code' => $d->pulsa_code,
                    'nama' =>$d->pulsa_nominal,
                    'harga' => $d->pulsa_price,
                    'details' => $d->pulsa_details,
                ]);
            }
            $sorted = $response->sortBy('harga')->values();
            return $sorted;
        }
    }

}
