<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'reg_villages';

    protected $fillable = [
        'name', 'district_id'
    ];

    public function kecamatan()
    {
        return $this->belongsTo('Modules\Cabang\Entities\Kecamatan', 'district_id');
    }
}
