<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama', 'wilayah_id', 'alamat', 'postal_code'
    ];

    protected $appends = [
        'daerah'
    ];

    public function wilayah()
    {
        return $this->belongsTo('Modules\Cabang\Entities\Kelurahan', 'wilayah_id');
    }

    public function getDaerahAttribute($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->provinsi->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->name)).', Kec. ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->name));
        return  $daerah;
    }
}
