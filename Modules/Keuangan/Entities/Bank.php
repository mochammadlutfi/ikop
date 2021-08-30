<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama', 'kode', 'no_rekening', 'atas_nama', 'icon'
    ];

}
