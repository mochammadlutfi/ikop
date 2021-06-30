<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class AkunKlasifikasi extends Model
{
    protected $table = 'akun_klasifikasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama', 'induk_id'
    ];

    public function sub()
    {
        return $this->hasMany('Modules\Keuangan\Entities\AkunKlasifikasi', 'induk_id');
    }

    public function akun()
    {
        return $this->hasMany('Modules\Keuangan\Entities\Akun', 'klasifikasi_id', 'id');
    }
}
