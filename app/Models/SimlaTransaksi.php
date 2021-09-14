<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimlaTransaksi extends Model
{
    protected $table = 'simla_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','no_transaksi', 'anggota_id', 'tujuan', 'type', 'amount'
    ];

    public function transaksi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Transaksi', 'no_transaksi', 'no_transaksi');
    }

    public function anggota()
    {
        return $this->belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id', 'anggota_id');
    }
}
