<?php

namespace Modules\Anggota\Entities;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'anggota_alamat';
    protected $primaryKey = 'alamat_id';

    protected $fillable = [
        'anggota_id', 'domisili', 'wilayah_id', 'pos', 'alamat'
    ];

    protected $appends = [
        'daerah', 'alamat_lengkap'
    ];

    public function anggota(){
        return $this->belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id');
    }

    public function wilayah()
    {
        return $this->belongsTo('Modules\Wilayah\Entities\Kelurahan', 'wilayah_id');
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

    public function getAlamatLengkapAttribute($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->provinsi->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->name)).', Kec. ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->name)).', ';
        $daerah .= $this->pos.', ';
        $daerah .= ucwords(strtolower($this->wilayah->name)).', ';
        $daerah .= $this->alamat;
        return  $daerah;
    }

}
