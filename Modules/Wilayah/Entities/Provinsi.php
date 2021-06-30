<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'reg_provinces';

    public function kota()
    {
        return $this->hasMany('Modules\Wilayah\Entities\Kota', 'id', 'regency_id');
    }

}
