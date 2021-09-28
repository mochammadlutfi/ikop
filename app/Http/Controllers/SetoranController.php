<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use DB;


class SetoranController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the setoran form.
     *
     * @return \Illuminate\Http\Response
     */
    public function sukarela(Request $request)
    {
        return view('setoran.sukarela');
    }
    
    public function sukarela_store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'jumlah' => 'required',
            'tgl' => 'required',
            'type' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'ID Anggota Wajib Diisi!',
            'kas_id.required' => 'Kas Wajib Diisi!',
            'jumlah.required' => 'Jumlah Simpanan Wajib Diisi!',
            'tgl.required' => 'Tanggal Transaksi Wajib Diisi!',
            'type.required' => 'Jenis Transaksi Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            $item = collect([
                array(
                    'keterangan' => 'Simpanan Sukarela',
                    'jumlah' => $request->jumlah,
                    'akun' => 14,
                ),
            ]);

            DB::beginTransaction();
            try{

                $transaksi = new Transaksi();
                $transaksi->nomor = $request->kd_transaksi;
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->service = 'simpanan';
                $transaksi->sub_service = 'sukarela';
                $transaksi->jenis = $request->type === 'isi saldo' ? 'setoran sukarela' : 'penarikan sukarela';
                $transaksi->teller_id   = auth()->guard('admin')->user()->id;
                $transaksi->keterangan = $request->keterangan;
                $transaksi->total = $request->jumlah;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->status = 1;
                $transaksi->save();


                $line = new TransaksiLine();
                $line->jumlah = $request->jumlah;
                $line->keterangan = 'Simpanan Sukarela';
                $line->akun_id = 14;
                $line->user_id = auth()->guard('admin')->user()->id;
                $line->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->line()->save($line);

                $payment = new TransaksiBayar();
                $payment->method = 'Tunai';
                $payment->jumlah = $request->jumlah;
                $payment->status = 'confirm';
                $transaksi->pembayaran()->save($payment);


                $simla = new SimlaTransaksi();
                $simla->anggota_id = $request->anggota_id;
                $simla->type = $request->type;
                $simla->jumlah = $request->jumlah;
                // $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->simla()->save($simla);

                $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
                if($request->type === 'deposit')
                {
                    $wallet->increment('sukarela', $request->jumlah);
                }else{
                    $wallet->decrement('sukarela', $request->jumlah);
                }

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                    'error' => $e,
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
                'invoice' => $transaksi->id,
            ]);
        }

    }

    
    /**
     * Show the setoran form.
     *
     * @return \Illuminate\Http\Response
     */
    public function wajib(Request $request)
    {
        return view('setoran.wajib');
    }

    /**
     * Store the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function wajib_store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'tgl' => 'required',
            'periode' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Koperasi Wajib Diisi!',
            'periode.required' => 'Periode Pembayaran Wajib Diisi!',
            'tgl.required' => 'Tanggal Setoran Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            $pay_check = SimkopTransaksi::where('anggota_id', $request->anggota_id)
            ->whereMonth('periode', Date::createFromFormat('d F Y', '1 '.$request->periode)->format('m'))
            ->whereYear('periode', Date::createFromFormat('d F Y', '1 '.$request->periode)->format('Y'))
            ->first();

            if($pay_check)
            {
                return response()->json([
                    'fail' => true,
                    'errors' => $validator->errors()
                ]);
            }

            $item = collect([
                array(
                    'keterangan' => 'Simpanan Wajib',
                    'jumlah' => 100000,
                    'akun' => 4,
                ),
            ]);
            
            if(!empty($request->jml_sosial)){
                $item = $item->push([
                    'keterangan' => 'Simpanan Sosial',
                    'jumlah' => $request->jml_sosial,
                    'akun' => 9,
                ]);
            }

            DB::beginTransaction();
            Date::today()->format('H:i:s');
            try{
                $transaksi = new Transaksi();
                $transaksi->nomor = $request->kd_transaksi;
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->service = 'simpanan';
                $transaksi->sub_service = 'wajib';
                $transaksi->jenis = 'setoran wajib';
                $transaksi->total = 100000 + $request->jml_sosial;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->teller_id  = auth()->guard('admin')->user()->id;
                $transaksi->save();
                
                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->anggota_id  = $request->anggota_id;
                $pay_wajib->periode = Date::createFromFormat('d F Y', '1 '.$request->periode)->format('Y-m-d');
                $pay_wajib->jumlah = 100000;
                $pay_wajib->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');

                $transaksi->simkop()->save($pay_wajib);

                $payment = new TransaksiBayar();
                $payment->method = 'Tunai';
                $payment->jumlah = !empty($request->jml_sosial) ? 150000 : 100000;
                $payment->status = 'confirm';
                $transaksi->pembayaran()->save($payment);

                $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
                $wallet->increment('wajib', 100000);

                foreach($item as $i){
                    $line = new TransaksiLine();
                    $line->akun_id = $i['akun'];
                    $line->jumlah = $i['jumlah'];
                    $line->keterangan = $i['keterangan'];
                    $line->user_id = auth()->guard('admin')->user()->id;
                    $transaksi->line()->save($line);
                }

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                    'error' => $e,
                ]);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
                'invoice' => $transaksi->id,
            ]);
        }
    }


}