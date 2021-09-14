<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SimkopTransaksi extends Model
{
    protected $table = 'simkop_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','no_transaksi', 'anggota_id', 'jumlah', 'next_payment'
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
