<?php

namespace Modules\PPOB\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

 
use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiBayar;
use Modules\PPOB\Entities\TransaksiPPOB;
use App\Models\User;
use App\Helpers\Notification;

class PPOBController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $note = $request->all();
        $data = $note["data"];
        if($data["rc"] == "00"){
            $ppob = TransaksiPPOB::where('ref_id', $data["ref_id"])->first();
            $ppob->sn = $data["sn"];
            $ppob->save();

            $transaksi = Transaksi::where("id", $ppob->transaksi_id)->first();
            $transaksi->status = 1;
            $transaksi->save();
            $anggota = User::where('anggota_id', $transaksi->anggota_id)->first();

            $notif = [
                'title'       => 'Transaksi Berhasil',
                'description'  => "Terimakasih, Transaksi Kamu Telah Berhasil!",
                'transaksi_id' => $transaksi->id,
                'image'       => '',
            ];
            
            Notification::send_push_notif_to_device($anggota->device_id, $notif);

        }else{
            $ppob = TransaksiPPOB::where('ref_id', $data["ref_id"])->first();

            $transaksi = Transaksi::where("id", $ppob->transaksi_id)->first();
            $transaksi->status = 1;
            $transaksi->save();

            $pembayaran = TransaksiBayar::where('transaksi_id', $ppob->transaksi_id)->first();
            $pembayaran->status = "cancel";
            $pembayaran->save();
            $anggota = User::where('anggota_id', $transaksi->anggota_id)->first();
            
            $notif = [
                'title'       => 'Oops! Transaksi Gagal',
                'description'  => "Pembayaran transaksi kamu akan segera dikembalikan!",
                'transaksi_id' => $transaksi->id,
                'image'       => '',
            ];
            
            Notification::send_push_notif_to_device($anggota->device_id, $notif);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('ppob::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('ppob::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ppob::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
