<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Modules\Keuangan\Entities\Pembayaran;
class PembayaranCountWidget extends AbstractWidget
{


    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'use_jquery_for_ajax_calls' => true
    ];
    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 10;


    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $status = array('draft', 'pending');
        $data = Pembayaran::select('transaksi_bayar.*', 'b.nama as anggota_nama', 'b.anggota_id', 'a.nomor', 'c.logo as bank_logo')
        ->leftJoin('transaksi as a', 'a.id','transaksi_bayar.transaksi_id')
        ->leftJoin('anggota as b', 'b.anggota_id','a.anggota_id')
        ->leftJoin('bank as c', 'c.id','transaksi_bayar.bank_id')
        ->whereIn('transaksi_bayar.status', $status)
        ->orderBy('created_at', 'DESC')->get()->count();

        return view('widgets.pembayaran_count_widget', [
            'data' => $data,
        ]);
    }
}
