<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    protected $table = 'transaksi_kas';
    protected $primaryKey = 'kd_trans_kas';
    public $incrementing = false;

    protected $fillable = [
        'kd_trans_kas', 'no_transaksi', 'kas_id', 'jumlah', 'keterangan', 'jenis', 'tujuan', 'akun_id', 'user_id'
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
