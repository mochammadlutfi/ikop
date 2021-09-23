<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use App\Models\User;
use Modules\Keuangan\Entities\Transaksi;
use Modules\PPOB\Entities\TransaksiPPOB;
use Modules\Keuangan\Entities\TransaksiBayar;
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
use Hash;
use GuzzleHttp\Client;
use IakID\IakApiPHP\IAK;
use IakID\IakApiPHP\Services\IAKPrepaid;
use IakID\IakApiPHP\Services\IAKPostpaid;

class PPOBController extends Controller
{

    private $apikey;
    private $username;
    private $client;
    private $prepaid;
    public function __construct()
    {
        $this->prepaid = new IAKPrepaid();
        $this->postpaid = new IAKPostpaid();
        $this->client = new Client();
        $this->username = "089656466525";
        $this->apikey = "3965e5b9f739b3ac";
    }

    
    public function index(Request $request){
        if($request->operator == 'Operator'){
            return response()->json([
                'message' => "Operator Not Found",
                'fail'=> true
            ],400);
        }else{
            $param = array(
                'type' => $request->type,
                'operator' => $request->operator,
                'status' => 'active'
            );
            $prepaid = $this->prepaid->pricelist($param);

            if($prepaid["data"]["rc"] == "00"){
                $d = collect($prepaid["data"]["pricelist"]);
                if($request->type == 'pulsa'){
                    $d = $d->filter(function ($item) {
                        return is_numeric(data_get($item, 'product_nominal'));
                    })->values();

                    $d = collect($d)->map(function($collection, $key) {
                        $collect = (object)$collection;
                        return [
                            "product_code" => $collect->product_code,
                            "product_description" => $collect->product_description,
                            "product_nominal" => $collect->product_nominal,
                            "product_details" => $collect->product_details,
                            "product_price" => $this->pulsa_price($collect->product_nominal),
                            "product_type" => $collect->product_type,
                            "active_period" => $collect->active_period,
                            "status" => $collect->status,
                            "icon_url" => $collect->icon_url,
                        ];
                    });
                }
                $data = $d->sortBy('product_price')->values();
                return response()->json([
                    'data' => $data,
                    'fail'=> false
                ],200);
            }else{
                return response()->json([
                    'message' => "Invalid Content",
                    'fail'=> true
                ],400);
            }
        }

    }

    protected function pulsa_price($nominal){
        // $nominal = preg_replace("/[^a-zA-Z0-9\s]/", "", $data);
        $price = 0;
        if($nominal == "1000"){
            $price = 2000;
        }elseif($nominal == "2000"){
            $price = 3000;
        }elseif($nominal == "3000"){
            $price = 4000;
        }elseif($nominal == "5000"){
            $price = 6000;
        }elseif($nominal == "10000"){
            $price = 11000;
        }elseif($nominal == "15000"){
            $price = 16000;
        }elseif($nominal == "20000"){
            $price = 21000;
        }elseif($nominal == "25000"){
            $price = 26000;
        }elseif($nominal == "30000"){
            $price = 31000;
        }elseif($nominal == "40000"){
            $price = 41000;
        }elseif($nominal == "50000"){
            $price = 51000;
        }elseif($nominal == "100000"){
            $price = 100000;
        }elseif($nominal == "150000"){
            $price = 150000;
        }elseif($nominal == "200000"){
            $price = 200000;
        }elseif($nominal == "300000"){
            $price = 300000;
        }elseif($nominal == "500000"){
            $price = 500000;
        }elseif($nominal == "1000000"){
            $price = 1000000;
        }
        return $price;
    }

    protected function topUp(Request $request){
        $ref_id = uniqid('');
        $body = [
            "commands" => "topup",
            "username" => $this->username,
            "ref_id" => $ref_id,
            "sign"     => md5($this->username.$this->apikey.$ref_id),
            "hp" => $request->phone,
            "pulsa_code" => $request->code,
        ];
        $url = "https://testprepaid.mobilepulsa.net/v1/legacy/index";
        $client = new Client();
        $req = $client->request('POST', $url, [
            'headers' => [ 'Content-Type' => 'application/json' ],
            'body' => json_encode($body)
        ]);
        $response =  json_decode($req->getBody());

        return $response->data;
    }

    public function payment(Request $request)
    {

        $nomor = get_ppob_code();
        $anggota_id = $request->user()->anggota_id;
        $tgl = Date::now();
        $user = User::find($request->user()->id);
        if (Hash::check($request->secure_code, $user->secure_code)) {
            return response()->json([
                'fail' => true,
                'message' => 'Security Pin Salah!',
            ], 401);
        }else{
            DB::beginTransaction();
            try{
                $ppob = $this->topUp($request);
                if($ppob->status != 0){
                    return response()->json([
                        'fail' => true,
                        'message' => 'Terjadi Error Transaksi',
                    ], 403);
                }
    
                $transaksi = new Transaksi();
                $transaksi->nomor = $nomor;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->jenis = 'pembelian';
                $transaksi->service = 'ppob';
                $transaksi->sub_service = $request->type;
                $transaksi->total = $request->jumlah;
                $transaksi->tgl = $tgl->format('Y-m-d H:i:s');
                $transaksi->status = 0;
                $transaksi->save();
    
                $simla = new TransaksiPPOB();
                $simla->ref_id = $ppob->ref_id;
                $simla->code = $request->code;
                $simla->type = $request->type;
                $simla->operator = $request->operator;
                $simla->phone = $request->phone;
                $simla->harga_beli = $ppob->price;
                $simla->harga_jual = $request->jumlah;
                $transaksi->ppob()->save($simla);
    
                $payment = new TransaksiBayar();
                $payment->transaksi_id = $nomor;
                $payment->jumlah = -$request->jumlah;
                $payment->bank_id = $request->bank;
                $payment->method = "simla";
                $payment->admin_fee = 0;
                $payment->code = get_payment_code($tgl);
                $payment->tgl_bayar = $tgl->format('Y-m-d H:i:s');
                $payment->status = 'confirm';
                $transaksi->pembayaran()->save($payment);
    
                
                $simla = new SimlaTransaksi();
                $simla->anggota_id = $anggota_id;
                $simla->tujuan = '';
                $simla->type = 'pembelian';
                $simla->jumlah = -$request->jumlah;
                $transaksi->simla()->save($simla);
    
                // $response = collect([
                //     'id' => $transaksi->id,
                //     'code' => $payment->code,
                //     'admin_fee' => $payment->admin_fee,
                //     'jumlah' => (int)$payment->jumlah,
                //     'method' => $payment->method,
                //     'transaksi' => [
                //         'id' => $transaksi->id,
                //         'nomor' => $transaksi->nomor,
                //         'jenis' => ucwords('pembelian '.$payment->type),
                //         'status' => 0,
                //     ],
                // ]);

                $response = collect([
                    'transaksi_id' => $transaksi->id,
                ]);
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Transaksi',
                    'error' => $e,
                ]);
            }
            $fcm_token = $request->user()->device_id;
            $notif = [
                'title'       => 'Transaksi Sedang Di Verifikasi',
                'description' => "Transaksi Kamu saaat ini sedang kami proses!",
                'transaksi_id'=> $transaksi->id,
                'image'       => '',
            ];
            
            Notification::send_push_notif_to_device($fcm_token, $notif);
            DB::commit();
            return response()->json([
                'fail' => false,
                'data' => $response,
            ]);
        }
    }

    public function cekTagihan(Request $request){
        if($request->operator == 'Operator' || $request->phone == ''){
            return response()->json([
                'message' => "Operator Not Found",
                'fail'=> true
            ],401);
        }else{
            $ref_id = uniqid('');
            $param = array(
                'code' => $request->type,
                'hp' => $request->phone,
                'refId' => $ref_id,
            );
            if($request->type == 'BPJS'){
                $param['month'] = $request->month;
            }

            $postpaid = $this->postpaid->inquiry($param);
            return $postpaid;
            if($postpaid["data"]["response_code"] == "00"){
                return response()->json([
                    'data' => $postpaid["data"],
                    'fail'=> false
                ],200);
            }else{
                return response()->json([
                    'message' => "Invalid Phone",
                    'fail'=> false
                ],401);
            }
        }
    }


    public function cekPLN(Request $request){
        if($request->operator == 'Operator'){
            return response()->json([
                'message' => "Operator Not Found",
                'fail'=> true
            ],401);
        }else{
            $prepaid = $this->prepaid->inquiryPLN([
                'customerId' => $request->phone
            ]);
            if($prepaid["data"]["status"] == 1){
                return response()->json([
                    'data' => $prepaid["data"],
                    'fail'=> false
                ],200);
            }else{
                return response()->json([
                    'message' => "Invalid Phone",
                    'fail'=> false
                ],401);
            }
        }
    }


    public function postPaidCheck(Request $request){
        
    }

}
