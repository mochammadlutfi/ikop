<?php

namespace Modules\Simpanan\Http\Controllers;



use Modules\Anggota\Entities\Anggota;

use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiBayar;
use Modules\Keuangan\Entities\TransaksiLine;

use Modules\Simpanan\Entities\SimkopTransaksi;
use Modules\Simpanan\Entities\SimlaTransaksi;
use Modules\Simpanan\Entities\Wallet;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
Use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use Jenssegers\Date\Date;

use PDF;
class KoperasiController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function setoran(Request $request)
    {
        return view('simpanan::koperasi.form');
    }

    /**
     * Store the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'kas_id' => 'required',
            'tgl' => 'required',
            'periode' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Koperasi Wajib Diisi!',
            'kas_id.required' => 'Kas Koperasi Wajib Diisi!',
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = Transaksi::
        with(['simkop',])
        // ->join('transaksi_kas as a', 'a.transaksi_id', '=', 'transaksi.id')
        // ->join('kas as b', 'b.id', '=', 'a.kas_id')
        ->where('transaksi.id', $id)->first();
        // dd($data->toArray());
        return view('simpanan::koperasi.edit', compact('data'));
    }

    /**
     * Store the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'kas_id' => 'required',
            'tgl' => 'required',
            'periode' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Koperasi Wajib Diisi!',
            'kas_id.required' => 'Kas Koperasi Wajib Diisi!',
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


            $item = collect([]);
            
            if(!empty($request->jml_sosial)){
                $item = $item->push([
                    'keterangan' => 'Simpanan Sosial',
                    'jumlah' => $request->jml_sosial,
                    'akun' => 9,
                ]);
            }

            DB::beginTransaction();
            try{

                Date::today()->format('H:i:s');

                $transaksi = Transaksi::where('transaksi_id', $request->id)->first();
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->teller_id  = auth()->guard('admin')->user()->id;
                $transaksi->keterangan = $request->keterangan;
                $transaksi->total = 100000 + $request->jml_sosial;
                $transaksi->tgl_transaksi = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->save();

                $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
                $wallet->increment('wajib', 100000);
                
                if($wallet->sosial !== $request->jml_sosial){
                    $wallet->decrement('sosial', $wallet->sosial);
                    $wallet->increment('sosial', $request->jml_sosial);
                }

                $sosial = TransaksiLine::where('transaksi_id', $request->id)->where('akun_id', 9)->first();
                if(empty($request->jml_sosial)){
                    $sosial->destroy();
                }elseif($request->jml_sosial !== $sosial->jumlah){
                    $sosial->jumlah = $request->jml_sosial;
                    $sosial->save();
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
                'invoice' => $request->kd_transaksi,
            ]);
        }
    }

    public function riwayat(Request $request)
    {
        if ($request->ajax()) {
            $keyword  = $request->keyword;
            $tgl_mulai = Carbon::parse($request->tgl_mulai);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);

            $result = SimkopTransaksi::select('a.anggota_id', 'simkop_transaksi.periode', 'a.nama', 'b.id', 'b.total', 'd.nama as teller', 'b.jenis', 'b.nomor', 'b.tgl')
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'simkop_transaksi.anggota_id')
            ->leftJoin('transaksi as b', 'b.id', '=', 'simkop_transaksi.transaksi_id')
            ->leftJoin('admins as c', 'c.id', '=', 'b.teller_id')
            ->leftJoin('anggota as d', 'd.anggota_id', '=', 'c.anggota_id')
            // with([
            //     'anggota' => function($query) use ($keyword) {
            //         $query->select('anggota_id','nama');
            //     },
            //     'teller' => function($query) {
            //         $query->select('id', 'anggota_id');
            //     },
            //     'simkop'
            // ])
            // ->where(function($q){
            //     $q->where('jenis', 'pendaftaran')
            //       ->orWhere('jenis', 'setoran wajib');
            // })
            ->where(function ($query) use ($keyword){
                return $query->where('a.anggota_id', 'like', '%' . $keyword . '%')
                ->orWhere('a.nama', 'like', '%' . $keyword . '%');
            })
            ->orderBy('tgl', 'DESC')
            ->paginate(20);
            return response()->json($result);
        }

        return view('simpanan::koperasi.riwayat');
    }

    public function tunggakan(Request $request)
    {
        if ($request->ajax()) {
            $keyword  = $request->keyword;
            $data = Anggota::with(['last_simkop'])
            ->select('anggota_id', 'nama', 'tgl_gabung')
            ->where(function($q) use ($keyword){
                $q->where('anggota_id', 'like', '%' . $keyword . '%')
                  ->orWhere('nama', 'like', '%' . $keyword . '%');
            })
            ->orderBy('anggota_id', 'DESC')->paginate(20);
            $data->each(function ($item) {
                $item->setAppends(['tunggakan_simkop']);
            });

            return response()->json($data);
        }
        return view('simpanan::koperasi.tunggakan');
    }

    public function tunggakan_detail($id, Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::with(['last_simkop'])
            ->select('anggota_id', 'nama', 'tgl_gabung')
            ->where('anggota_id', $id)
            ->orderBy('anggota_id', 'DESC')->first();
            $data->setAppends(['tunggakan_simkop']);

            return response()->json($data);
        }
        return view('simpanan::koperasi.tunggakan');
    }

    public function invoice($id)
    {
        $data = Transaksi::with(['line', 'pembayaran'])
        ->where('id', $id)->first();
        return view('simpanan::koperasi.invoice', compact('data'));
    }

    public function invoice_print($id)
    {
        $invoice = Transaksi::with('anggota')->where('no_transaksi', $id)->firstorfail();
        $pay = SimkopTransaksi::where('no_transaksi',$id)->get();

        $pdf = PDF::loadView('simpanan::koperasi.slip', compact('invoice', 'pay'));
        return $pdf->stream('SLIP-SETORAN-KOPERASI-'.$invoice->no_transaksi.'.pdf');

        // return view('simpanan::koperasi.slip', compact('invoice', 'pay', 'alamat'));
    }

}
