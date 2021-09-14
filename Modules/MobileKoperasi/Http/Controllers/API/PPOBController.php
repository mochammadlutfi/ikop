<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Simpanan\Entities\Wallet;


use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
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
        if($request->operator == 'Operator'){
            return response()->json([
                'message' => "Operator Not Found",
                'fail'=> true
            ],401);
        }else{
            $body = [
                "commands" => "pricelist",
                "username" => $this->username,
                "sign"     => md5($this->username.$this->apikey.'pl'),
                "status"   => "active"
            ];
            $url = "https://testprepaid.mobilepulsa.net/v1/legacy/index/" .$request->type. "/".$request->operator;
            $client = new Client();
            $req = $client->request('POST', $url, [
                'headers' => [ 'Content-Type' => 'application/json' ],
                'body' => json_encode($body)
            ]);
            $response = json_decode($req->getBody());
            $data = $this->sorter($response);
            // $c = collect($response->data);
            // if($request->type == 'pulsa'){
            //     $data = $this->pulsa($response);
            // }else if($request->type == 'data'){
            //     $data =  $this->data($response);
            // }else{
                // $data = $c->sortBy('pulsa_nominal')->values();
            // }
        }
        
        return response()->json([
            'data' => $data,
            // 'data' => $response->data,
            'fail'=> false
        ],200);
    }

    protected function sorter($data){
        if($data){
            $response = collect();
            foreach($data->data as $d){
                $response->push([
                    'type' => $d->pulsa_type,
                    'code' => $d->pulsa_code,
                    'nama' => $d->pulsa_op.' '.$d->pulsa_nominal,
                    'nominal' => $d->pulsa_nominal,
                    'harga' => (int)$d->pulsa_price,
                    'details' => 'Masa Aktif '.$d->masaaktif. ' hari',
                    'masaaktif' => $d->masaaktif,
                    'icon_url' => $d->icon_url,
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
        DB::beginTransaction();
        try{
            $nomor = get_ppob_code($request->type);
            $anggota_id = $request->user()->anggota_id;
            $tgl = Date::now();

            $ppob = $this->topUp($request);
            // dd($ppob);
            // dd($ppob);
            $transaksi = new Transaksi();
            $transaksi->nomor = $nomor;
            $transaksi->anggota_id = $anggota_id;
            $transaksi->jenis = 'pembelian';
            $transaksi->service = $request->type;
            $transaksi->sub_service = 'telkomsel';
            $transaksi->total = $request->jumlah;
            $transaksi->tgl = $tgl->format('Y-m-d H:i:s');
            $transaksi->status = 0;
            $transaksi->save();

            $simla = new TransaksiPPOB();
            $simla->ref_id = $ppob->ref_id;
            $simla->type = $request->type;
            $simla->operator = $request->operator;
            $simla->harga_beli = $ppob->price;
            $simla->harga_jual = $request->jumlah;
            $transaksi->ppob()->save($simla);

            $payment = new TransaksiBayar();
            $payment->transaksi_id = $nomor;
            $payment->jumlah = -$request->jumlah;
            $payment->bank_id = $request->bank;
            $payment->method = $request->method;
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
            

            // $kas = new TransaksiKas();
            // $kas->kas_id = $request->kas_id;
            // $kas->jumlah = $request->jumlah;
            // $kas->keterangan = 'Simpanan Sukarela';
            // $kas->jenis = $request->type === 'deposit' ? 'pemasukan' : 'pengeluaran';
            // $kas->akun_id = 14;
            // $kas->user_id = auth()->user()->id;
            // $kas->tgl = $tgl->format('Y-m-d H:i:s');
            // $kas->created_at = $tgl->format('Y-m-d H:i:s');
            // $transaksi->transaksi_kas()->save($kas);

            // $bank = Bank::where('id', $request->bank)->first();
            // $bank->logo = 'http://192.168.1.3/bumaba/public/'. $bank->logo;

            $response = collect([
                'id' => $payment->id,
                'code' => $payment->code,
                'admin_fee' => $payment->admin_fee,
                'jumlah' => (int)$payment->jumlah,
                'method' => $payment->method,
                'transaksi' => [
                    'id' => $transaksi->id,
                    'nomor' => $transaksi->nomor,
                    'jenis' => ucwords('pembelian '.$payment->type),
                    'status' => 0,
                ],
            ]);

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Terjadi Error Transaksi',
                'error' => $e,
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'data' => $response,
        ]);
    }

    public function cekTagihan(Request $request){
        if($request->operator == 'Operator'){
            return response()->json([
                'message' => "Operator Not Found",
                'fail'=> true
            ],401);
        }else{
            $ref_id = uniqid('');
            $body = [
                "commands" => "inq-pasca",
                "username" => $this->username,
                "ref_id"   => $ref_id,
                "hp" => $request->phone,
                "code" => "PLNPOSTPAID",
                "sign"     => md5($this->username.$this->apikey.$ref_id),
            ];
            $url = "https://testpostpaid.mobilepulsa.net/api/v1/bill/check";
            $client = new Client();
            $req = $client->request('POST', $url, [
                'headers' => [ 'Content-Type' => 'application/json' ],
                'body' => json_encode($body)
            ]);
            $response = json_decode($req->getBody());
            // if($request->type == 'pulsa'){
            //     $data = $this->pulsa($response);
            // }else if($request->type == 'data'){
            //     $data =  $this->data($response);
            // }else{
            //     $data = $response;
            // }
        }
        
        return response()->json([
            'data' => $response,
            'fail'=> false
        ],200);
    }


}
