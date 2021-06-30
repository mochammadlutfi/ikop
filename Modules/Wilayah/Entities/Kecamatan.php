<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'reg_districts';

    protected $fillable = [
        'name', 'district_id', 'alamat', 'postal_code'
    ];

    public function kota()
    {
        return $this->belongsTo('Modules\Wilayah\Entities\Kota', 'regency_id', 'id');
    }
}
