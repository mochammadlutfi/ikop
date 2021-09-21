<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class TransaksiLine extends Model
{
    protected $table = 'transaksi_line';

    protected $fillable = [
        'id', 'transaksi_id', 'type', 'jumlah', 'jenis', 'akun_id', 'user_id'
    ];

    public function kas()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Kas', 'kas_id', 'id');
    }

    public function transaksi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Transaksi', 'no_transaksi');
    }

    public function tujuan()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Kas', 'tujuan', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Kas', 'tujuan', 'kas_id');
    }

    public function akun()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Akun', 'akun_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Admin', 'user_id', 'id');
    }

    public function getUserNama($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->provinsi->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->name)).', Kec. ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->name));
        return  $daerah;
    }
}
