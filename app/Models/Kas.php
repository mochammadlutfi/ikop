<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama', 'saldo'
    ];

    public function transaksi()
    {
        return $this->hasMany('Modules\Keuangan\Entities\TransaksiKas');
    }

    public function trans_kas_untuk()
    {
        return $this->hasOne('Modules\Keuangan\Entities\TransaksiKas', 'kas_id', 'tujuan');
    }
}
