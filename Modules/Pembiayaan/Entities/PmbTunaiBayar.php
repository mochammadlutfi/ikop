<?php

namespace Modules\Pembiayaan\Entities;


use Illuminate\Database\Eloquent\Model;

class PmbTunaiBayar extends Model
{
    protected $table = 'pmb_tunai_bayar';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'pmb_tunai_detail_id', 'metode', 'jumlah', 'denda', 'tgl_bayar', 'teller',
    ];

    public function transaksi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Transaksi', 'no_transaksi');
    }

    public function anggota()
    {
        return $this->belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id', 'anggota_id');
    }

}
