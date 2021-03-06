<?php

namespace Modules\MobileKoperasi\Http\Controllers\API;

use Modules\Keuangan\Entities\Bank;
use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiLine;
use Modules\Simpanan\Entities\Wallet;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Simpanan\Entities\SimkopTransaksi;
use Modules\Keuangan\Entities\TransaksiBayar;

use App\Helpers\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use DB;
use Date;
class PaymentController extends Controller
{
    
     /**
     * Get Saldo Simla.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bank(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;

        $data = Bank::latest()->get();
        return response()->json([
            'data' => $data,
            'fail' => false,
        ], 200);
    }

    public function confirm(Request $request)
    {
        $anggota_id = $request->user()->anggota_id;
        // dd($request->all());
        DB::beginTransaction();
        try{

            $data = TransaksiBayar::select('transaksi_bayar.*', 'a.sub_service')
            ->join('transaksi as a', 'a.id', '=', 'transaksi_bayar.transaksi_id')
            ->where('transaksi_bayar.id', $request->id)->first();
            $data->status = 'draft';
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Terjadi Error Transaksi',
                'error' => $e,
            ]);
        }
        // $transaksi_id = $data->transaksi_id;
        // $slug = $data->sub_service;
        $fcm_token = $request->user()->device_id;
        $notif = [
            'title'       => 'Transaksi Sedang Di Verifikasi',
            'description' => "Transaksi Kamu saaat ini sedang kami proses!",
            'transaksi_id'=> $data->transaksi_id,
            'image'       => '',
        ];
        Notification::send_push_notif_to_device($fcm_token, $notif);
        DB::commit();
        return response()->json([
            'fail' => false,
            'transaksi_id' => $data->transaksi_id,
            'slug' => $data->sub_service,
        ]);
    }

    public function detail($id)
    {
        DB::beginTransaction();
        try{

            $data = TransaksiBayar::select('transaksi_bayar.id', 'transaksi_bayar.method', 'transaksi_bayar.jumlah', 'transaksi_bayar.code', 'transaksi_bayar.admin_fee', 'transaksi_bayar.bank_id', 'transaksi_bayar.transaksi_id')
            ->with(['transaksi' => function($q){
                $q->select(['id', 'nomor', 'jenis', 'status']);
            },'bank'])
            ->whereHas('transaksi', function($q) use($id){
                $q->where('transaksi.id', $id);
            })->first();
            
            $data->jumlah = (int)$data->jumlah;
            $data->admin_fee = (int)$data->admin_fee;

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
            'data' => $data,
        ]);
    }

    public function index(Request $request){
        $rules = [
            'jumlah' => 'required',
            'bank' => 'required',
        ];

        $pesan = [
            'jumlah.required' => 'Nominal Jumlah Wajib Diisi!',
            'bank.required' => 'Tanggal Transaksi Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            if($request->slug == 'simla'){
                $response =  $this->sukarela($request);
            }elseif($request->slug == 'wajib'){
                $response =  $this->wajib($request);
            }

            return $response;
        }
    }

    protected function sukarela(Request $request)
    {
        DB::beginTransaction();
        try{
            $nomor = get_simla_nomor();
            $anggota_id = $request->user()->anggota_id;
            $tgl = Date::now();

            $item = collect([
                array(
                    'keterangan' => 'Simpanan Sukarela',
                    'jumlah' => $request->jumlah,
                    'akun' => 14,
                ),
            ]);

            $payment_code = get_payment_code($tgl);

            $transaksi = new Transaksi();
            $transaksi->nomor = $nomor;
            $transaksi->anggota_id = $anggota_id;
            $transaksi->jenis = 'setoran sukarela';
            $transaksi->service = 'simpanan';
            $transaksi->sub_service = 'sukarela';
            $transaksi->total = $request->jumlah + 2000;
            $transaksi->tgl = $tgl->format('Y-m-d H:i:s');
            $transaksi->status = 0;
            $transaksi->save();

            $simla = new SimlaTransaksi();
            $simla->anggota_id = $anggota_id;
            $simla->type = 'isi saldo';
            $simla->jumlah = $request->jumlah;
            $transaksi->simla()->save($simla);

            $payment = new TransaksiBayar();
            $payment->transaksi_id = $nomor;
            $payment->jumlah = $request->jumlah;
            $payment->bank_id = $request->bank;
            $payment->method = 'Transfer';
            $payment->admin_fee = 2000;
            $payment->code = $payment_code;
            $payment->tgl_bayar = $tgl->format('Y-m-d H:i:s');
            $transaksi->pembayaran()->save($payment);

            $line = new TransaksiLine();
            $line->jumlah = $request->jumlah;
            $line->keterangan = 'Simpanan Sukarela';
            $line->akun_id = 14;
            $transaksi->line()->save($line);

            $line = new TransaksiLine();
            $line->jumlah = 2000;
            $line->keterangan = 'Administrasi';
            $line->akun_id = 12;
            $transaksi->line()->save($line);


            $bank = Bank::where('id', $request->bank)->first();

            $response = collect([
                'id' => $payment->id,
                'code' => $payment->code,
                'admin_fee' => $payment->admin_fee,
                'jumlah' => (int)$payment->jumlah,
                'method' => 'Transfer',
                'transaksi' => [
                    'id' => $transaksi->id,
                    'nomor' => $transaksi->nomor,
                    'jenis' => 'Isi Saldo',
                    'status' => 0,
                ],
                'bank' => $bank,
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

    protected function wajib(Request $request)
    {
        DB::beginTransaction();
        try{
            $nomor = get_simkop_nomor();
            $anggota_id = $request->user()->anggota_id;
            $tgl = Date::now();
            $periode = str_replace(array( '[', ']' ), '', $request->tagihan_id);
            if(!is_array($periode)){
                $periode = explode(', ',str_replace(array( '[', ']' ), '', $request->tagihan_id));
            }

            if($request->method == 'Transfer'){
                $payment_code = get_payment_code($tgl);
                $total = $request->jumlah + 2000;
            }else{
                $total = $request->jumlah;
            }

            $transaksi = new Transaksi();
            $transaksi->nomor = $nomor;
            $transaksi->anggota_id = $anggota_id;
            $transaksi->jenis = 'Setoran Wajib';
            $transaksi->service = 'simpanan';
            $transaksi->sub_service = 'wajib';
            $transaksi->total = $total;
            $transaksi->tgl = $tgl->format('Y-m-d H:i:s');
            $transaksi->status = 0;
            $transaksi->save();
            
            foreach($periode as $p)
            {
                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->anggota_id  = $anggota_id;
                $pay_wajib->periode = Date::createFromFormat('d F Y', '1 '.$p)->format('Y-m-d');
                $pay_wajib->jumlah = 100000;
                $transaksi->simkop()->save($pay_wajib);

                $line = new TransaksiLine();
                $line->akun_id = 4;
                $line->jumlah = $request->jumlah;
                $line->keterangan = 'Simpanan Wajib';
                $transaksi->line()->save($line);
            }

            $payment = new TransaksiBayar();
            $payment->transaksi_id = $nomor;
            $payment->jumlah = $request->jumlah;
            $payment->bank_id = $request->bank;
            $payment->method = $request->method == 'simla' ? 'Simpanan Sukarela' : 'Transfer';
            $payment->admin_fee = 2000;
            $payment->code = get_payment_code($tgl);
            $payment->tgl_bayar = $tgl->format('Y-m-d H:i:s');
            $transaksi->pembayaran()->save($payment);

            if($request->method == 'simla'){
                $simla = new SimlaTransaksi();
                $simla->anggota_id = $anggota_id;
                $simla->type = 'pembayaran';
                $simla->jumlah = -$request->jumlah;
                $transaksi->simla()->save($simla);

                $response = $transaksi->id;
            }else{
                $line = new TransaksiLine();
                $line->jumlah = 2000;
                $line->keterangan = 'Administrasi';
                $line->akun_id = 12;
                $transaksi->line()->save($line);

                $bank = Bank::where('id', $request->bank)->first();

                $response = collect([
                    'id' => $payment->id,
                    'code' => $payment->code,
                    'admin_fee' => $payment->admin_fee,
                    'jumlah' => (int)$payment->jumlah,
                    'transaksi' => [
                        'id' => $transaksi->id,
                        'nomor' => $transaksi->nomor,
                        'jenis' => 'Isi Saldo',
                        'status' => 0,
                    ],
                    'bank' => $bank,
                ]);
                
            }
            $fcm_token = $request->user()->device_id;
                
            $data = [
                'title'       => 'Menunggu Pembayaran',
                'description' => "Silahkan Lakukan Transfer Ke Rekening Koperasi Bumaba",
                'transaksi_id'=> $transaksi->id,
                'image'       => '',
            ];
            Notification::send_push_notif_to_device($fcm_token, $data);

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

}
