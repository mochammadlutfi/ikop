<?php

namespace Modules\Pembiayaan\Entities;


use Illuminate\Database\Eloquent\Model;

class PmbTunai extends Model
{
    protected $table = 'pmb_tunai';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'anggota_id', 'durasi', 'jumlah', 'jumlah_bunga', 'angsuran_pokok', 'angsuran_bunga', 'biaya_admin',  'status'
    ];

    public function transaksi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Transaksi', 'no_transaksi');
    }

    public function anggota()
    {
        return $this->belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id', 'anggota_id');
    }

    public function detail()
    {
        return $this->belongsTo('Modules\Pembiayaan\Entities\PmbTunaiDetail', 'pmb_tunai_id');
    }

}
