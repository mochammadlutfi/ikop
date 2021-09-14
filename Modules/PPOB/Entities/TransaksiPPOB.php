<?php

namespace Modules\PPOB\Entities;


use Illuminate\Database\Eloquent\Model;

class TransaksiPPOB extends Model
{
    protected $table = 'transaksi_ppob';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','transaksi_id', 'anggota_id', 'jumlah', 'next_payment'
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
