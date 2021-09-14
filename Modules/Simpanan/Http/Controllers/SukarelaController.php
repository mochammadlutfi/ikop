<?php
namespace Modules\Simpanan\Http\Controllers;



use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Modules\Simpanan\Entities\Wallet;
use Modules\Simpanan\Entities\SimlaTransaksi;

use App\Http\Controllers\Controller;
Use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use PDF;


class SukarelaController extends Controller
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
        $type = 'isi saldo';
        return view('simpanan::sukarela.form', compact('type'));
    }
    
    public function store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'kas_id' => 'required',
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
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->jenis = $request->type === 'deposit' ? 'setoran sukarela' : 'penarikan sukarela';
                $transaksi->teller_id   = auth()->user()->id;
                $transaksi->item = json_encode($item);
                $transaksi->total = $request->jumlah;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->save();


            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Transaksi',
                    'error' => $e,
                ]);
            }

            try{

                $kas = new TransaksiKas();
                $kas->kd_trans_kas = get_no_transaksi_kas($request->type === 'deposit' ? 'pemasukan' : 'pengeluaran');
                $kas->kas_id = $request->kas_id;
                $kas->jumlah = $request->jumlah;
                $kas->keterangan = 'Simpanan Sukarela';
                $kas->jenis = $request->type === 'deposit' ? 'pemasukan' : 'pengeluaran';
                $kas->akun_id = 14;
                $kas->user_id = auth()->user()->id;
                $kas->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $kas->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->transaksi_kas()->save($kas);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Kas',
                    'error' => $e,
                ]);
            }


            try{

                $simla = new SimlaTransaksi();
                $simla->anggota_id = $request->anggota_id;
                $simla->type = $request->type;
                $simla->jumlah = $request->jumlah;
                $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
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
                'invoice' => $request->kd_transaksi,
            ]);
        }

    }


    public function edit($id, Request $request)
    {
        $data = SimlaTransaksi::with([
            'anggota' => function($query){
                $query->select('anggota_id','nama', 'no_ktp', 'no_hp');
            },
            'transaksi'
        ])
        ->where('no_transaksi', $id)->first();

        $kas = TransaksiKas::where('no_transaksi', $id)->first();


        return view('simpanan::sukarela.form.edit', compact('data', 'kas'));
    }
    
    public function update(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'kas_id' => 'required',
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
        // dd($request->all());

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

            if($request->type == 'deposit'){
                $type = 'setoran';
            }else if($request->type == 'withdrawal'){
                $type = 'penarikan';
            }

            DB::beginTransaction();
            try{

                $transaksi = Transaksi::where('no_transaksi', $request->kd_transaksi)->first();
                
                $transaksi->anggota_id = $request->anggota_id;

                if(abs($transaksi->total) !== $request->jumlah){
                    $transaksi->total = $request->jumlah;
                    $transaksi->item = json_encode($item);
                }
                $transaksi->tgl_transaksi = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Transaksi',
                    'error' => $e,
                ]);
            }

            try{

                $kas = TransaksiKas::where('no_transaksi', $request->kd_transaksi)->first();
                $kas->kas_id = $request->kas_id;

                if(abs($kas->jumlah) !== $request->jumlah){
                    $kas->jumlah = $request->jumlah;
                }
                
                $kas->akun_id = 14;
                $kas->user_id = auth()->user()->id;
                $kas->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $kas->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $kas->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Kas',
                    'error' => $e,
                ]);
            }


            try{

                $simla = SimlaTransaksi::where('no_transaksi', $request->kd_transaksi)->first();
                $simla->anggota_id = $request->anggota_id;
                $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $simla->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');

                if(abs($request->jumlah) !== $simla->amount){
                    $simla->amount = $request->jumlah;
                    $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();

                    if($request->type == 'deposit'){
                        $wallet->decrement('sukarela', $simla->amount);
                        $wallet->increment('sukarela', $request->jumlah);
                    }else{
                        $wallet->increment('sukarela', $simla->amount);
                        $wallet->decrement('sukarela', $request->jumlah);
                    }
                }

                $simla->save();


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

            $data = SimlaTransaksi::select('a.anggota_id', 'a.nama', 'simla_transaksi.jumlah', 'd.nama as teller', 'simla_transaksi.type', 'b.nomor', 'b.tgl', 'b.id')
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'simla_transaksi.anggota_id')
            ->leftJoin('transaksi as b', 'b.id', '=', 'simla_transaksi.transaksi_id')
            ->leftJoin('admins as c', 'c.id', '=', 'b.teller_id')
            ->leftJoin('anggota as d', 'd.anggota_id', '=', 'c.anggota_id')
            // with([
            //     'anggota' => function($query) use ($keyword) {
            //         $query->select('anggota_id','nama');
            //     },
            //     'teller' => function($query) {
            //         $query->select('id', 'anggota_id');
            //     },
            //     'simla'
            // ])
            // ->whereBetween('b.tgl', [$tgl_mulai, $tgl_akhir])
            ->where(function ($query) use ($keyword) {
                return $query->where('a.anggota_id', 'like', '%' . $keyword . '%')
                ->orWhere('a.nama', 'like', '%' . $keyword . '%')
                ->orWhere('b.jenis', 'like', '%' . $keyword . '%')
                ->orWhere('b.nomor', 'like', '%' . $keyword . '%')
                ->orWhere('d.nama', 'like', '%' . $keyword . '%');
            })
            ->orderBy('tgl', 'DESC')
            ->paginate(20);

            return response()->json($data);
        }
        
        return view('simpanan::sukarela.riwayat');
    }

    public function invoice($id)
    {
        $data = Transaksi::find($id);
        if($data->jenis == 'penarikan sukarela')
        {
            $data->title = 'SLIP PENARIKAN';
            $data->jenis = 'Penarikan Tunai';

        }else if($data->jenis == 'setoran sukarela')
        {
            $data->title = 'SLIP SETORAN';
            $data->jenis = 'Setoran Tunai';
        }

        return view('simpanan::sukarela.invoice', compact('data'));

    }

    public function invoice_print($id)
    {
        $invoice = Transaksi::find($id);

        if($invoice->jenis == 'penarikan sukarela')
        {
            $pdf = PDF::loadView('simpanan::sukarela.slip_penarikan', compact('invoice'));
            return $pdf->stream('SLIP-PENARIKAN-SUKARELA-'.$invoice->no_transaksi.'.pdf');

        }else if($invoice->jenis == 'setoran sukarela')
        {
            $pdf = PDF::loadView('simpanan::sukarela.slip_setoran', compact('invoice'));
            return $pdf->stream('SLIP-SETORAN-SUKARELA-'.$invoice->no_transaksi.'.pdf');
        }
    }

    public function penarikan(Request $request)
    {
        if ($request->isMethod('get')){

            return view('simpanan::sukarela.form.penarikan');
        }else {
            $rules = [
                'id_anggota' => 'required',
                'kas_id' => 'required',
                'jumlah' => 'required',
                'tgl' => 'required',
            ];

            $pesan = [
                'id_anggota.required' => 'ID Anggota Wajib Diisi!',
                'kas_id.required' => 'Kas Wajib Diisi!',
                'jumlah.required' => 'Jumlah Simpanan Wajib Diisi!',
                'tgl.required' => 'Tanggal Transaksi Wajib Diisi!'
            ];

            $validator = Validator::make($request->all(), $rules, $pesan);
            if ($validator->fails()){
                return response()->json([
                    'fail' => true,
                    'errors' => $validator->errors()
                ]);
            }else{
                $wallet = Wallet::find($request->id_anggota);
                if($wallet->sukarela < $request->jumlah)
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => array(
                            'jumlah' => 'Jumlah Saldo Kurang'
                        )
                    ]);
                }else{
                    
                    $item = collect([
                        array(
                            'produk' => 'Sukarela',
                            'jumlah' => $request->jumlah,
                        ),
                    ]);

                    DB::beginTransaction();
                    try{
                    $transaksi = new Transaksi();
                    $transaksi->no_transaksi = $request->no_invoice;
                    $transaksi->anggota_id = $request->id_anggota;
                    $transaksi->jenis = 'penarikan sukarela';
                    $transaksi->teller_id   = auth()->user()->id;
                    $transaksi->item = json_encode($item);
                    $transaksi->total = $request->jumlah;
                    $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                    $transaksi->save();
                    }catch(\Exception $e){
                        DB::rollback();
                        return response()->json([
                            'fail' => true,
                            'pesan' => 'Error Transaksi',
                        ]);
                    }

                    try{
                        if(PaySukarela::where('anggota_id', $request->id_anggota)->get()->count() > 0)
                        {
                            $sukarela = PaySukarela::where('anggota_id', $request->id_anggota)->latest()->first();
                            $debit = $request->jumlah - $sukarela->debit;
                        }else{
                            $debit = $request->jumlah;
                        }
                        $pay_sukarela = new PaySukarela();
                        $pay_sukarela->no_transaksi = $request->no_invoice;
                        $pay_sukarela->anggota_id  = $request->id_anggota;
                        $pay_sukarela->jumlah = -$request->jumlah;
                        $pay_sukarela->debit = $debit;
                        $pay_sukarela->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                        $pay_sukarela->save();
                    }catch(\Exception $e){
                        DB::rollback();
                        return response()->json([
                            'fail' => true,
                            'pesan' => 'Error Sukarela',
                        ]);
                    }

                    try{
                        $kas = new TransaksiKas();
                        $kas->kd_trans_kas = TransaksiHelp::get_no_transaksi_kas('pengeluaran');
                        $kas->no_transaksi = $request->no_invoice;
                        $kas->kas_id = $request->kas_id;
                        $kas->jumlah = $request->jumlah;
                        $kas->keterangan = 'Penarikan Sukarela';
                        $kas->jenis = 'pengeluaran';
                        $kas->akun_id = 14;
                        $kas->user_id = auth()->user()->id;
                        $kas->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                        $kas->save();
                    }catch(\Exception $e){
                        DB::rollback();
                        return response()->json([
                            'fail' => true,
                            'pesan' => 'Error Kas',
                        ]);
                    }

                    try{
                        $w = Wallet::where('anggota_id', $request->id_anggota)->first();
                        $saldo_akhir = $w->saldo - $request->jumlah;
                        $update = Wallet::where('anggota_id', $request->id_anggota)->update(['sukarela' => $saldo_akhir]);
                    }catch(\Exception $e){
                        DB::rollback();
                        return response()->json([
                            'fail' => true,
                            'pesan' => 'Error Wallet',
                        ]);
                    }

                    DB::commit();
                    return response()->json([
                        'fail' => false,
                        'invoice' => $request->no_invoice,
                    ]);

                }
            }
        }

    }

    public function penarikan_store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'kas_id' => 'required',
            'jumlah' => 'required',
            'tgl' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'ID Anggota Wajib Diisi!',
            'kas_id.required' => 'Kas Wajib Diisi!',
            'jumlah.required' => 'Jumlah Simpanan Wajib Diisi!',
            'tgl.required' => 'Tanggal Transaksi Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
            if($wallet->sukarela < $request->jumlah){
                return response()->json([
                    'fail' => true,
                    'errors' => 'Saldo Kurang',
                ]);
            }

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
                $transaksi->no_transaksi = $request->kd_transaksi;
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->jenis = 'penarikan sukarela';
                $transaksi->teller_id   = auth()->user()->id;
                $transaksi->item = json_encode($item);
                $transaksi->total = $request->jumlah;
                $transaksi->tgl_transaksi = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->save();


            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Transaksi',
                    'error' => $e,
                ]);
            }

            try{

                $kas = new TransaksiKas();
                $kas->kd_trans_kas = get_no_transaksi_kas('pemasukan');
                $kas->no_transaksi = $request->kd_transaksi;
                $kas->kas_id = $request->kas_id;
                $kas->jumlah = $request->jumlah;
                $kas->keterangan = 'Simpanan Sukarela';
                $kas->jenis = 'pengeluaran';
                $kas->akun_id = 14;
                $kas->user_id = auth()->user()->id;
                $kas->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $kas->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $kas->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Kas',
                    'error' => $e,
                ]);
            }


            try{
                $simla = new SimlaTransaksi();
                $simla->no_transaksi = $request->kd_transaksi;
                $simla->anggota_id = $request->anggota_id;
                $simla->type = 'withdrawal';
                $simla->amount = $request->jumlah;
                $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $simla->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $simla->save();

                $wallet->decrement('sukarela', $request->jumlah);

            }catch(\Exception $e){
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


}
