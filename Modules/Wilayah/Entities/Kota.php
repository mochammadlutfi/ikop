<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'reg_regencies';

    public function provinsi()
    {
        return $this->belongsTo('Modules\Wilayah\Entities\Provinsi', 'province_id', 'id');
    }
}
