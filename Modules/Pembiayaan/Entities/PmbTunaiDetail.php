<?php

namespace Modules\Pembiayaan\Entities;


use Illuminate\Database\Eloquent\Model;

class PmbTunaiDetail extends Model
{
    protected $table = 'pmb_tunai_detail';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'pmb_tunai_id', 'angsuran_ke', 'jumlah_pokok', 'jumlah_bunga', 'tgl_tempo', 'status',
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
