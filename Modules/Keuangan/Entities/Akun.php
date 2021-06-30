<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama', 'klasifikasi_id', 'pemasukan', 'pengeluaran', 'status'
    ];

    public function klasifikasi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\AkunKlasifikasi', 'klasifikasi_id', 'id');
    }

    public function transaksi()
    {
        return $this->hasMany('Modules\Keuangan\Entities\TransaksiKas', 'id', 'akun_id');
    }
}
