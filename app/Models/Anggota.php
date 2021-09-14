<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Modules\Simpanan\Entities\SimkopTransaksi;
use Jenssegers\Date\Date;
class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'anggota_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'anggota_id', 'nama', 'email', 'no_ktp', 'jk', 'tmp_lahir', 'tgl_lahir', 'no_hp', 'no_telp', 'status_pernikahan', 'pendidikan', 'pekerjaan', 'nama_ibu', 'foto', 'ktp'
    ];

    protected $appends = [
        'status_badge',
    ];

    public function alamat()
    {
        return $this->hasMany('Modules\Anggota\Entities\Alamat', 'anggota_id', 'anggota_id');
    }

    public function simkop()
    {
        return $this->hasMany('Modules\Simpanan\Entities\SimkopTransaksi', 'anggota_id', 'anggota_id');
    }

    public function transaksi()
    {
        return $this->hasMany('Modules\Keuangan\Entities\Transaksi', 'no_transaksi', 'no_transaksi');
    }
    
    public function getAlamatFullAttribute()
    {
        $data = $this->alamat->where('domisili', 1)->first();
        $alamat = '';
        $alamat .= $data->alamat.', ';
        $alamat .= ucwords(strtolower($data->wilayah->name)).', ';
        $alamat .= $data->pos.', ';
        $alamat .= 'Kec. '.ucwords(strtolower($data->wilayah->kecamatan->name)).', ';
        $alamat .= ucwords(strtolower($data->wilayah->kecamatan->kota->name)).', ';
        $alamat .= ucwords(strtolower($data->wilayah->kecamatan->kota->provinsi->name));
        return $alamat;
    }

    public function getTunggakanSimkopAttribute($value)
    {
        $dari = Date::parse($this->tgl_gabung)->startOfMonth();
        $now = Date::now()->endOfMonth();
        $diff_in_months = $dari->diffInMonths($now);

        $nominal = 0;
        $jumlah = 0;
        $list = array();
        for($i = 0; $i <= $diff_in_months; $i++)
        {
            $bulan = SimkopTransaksi::where('anggota_id', $this->anggota_id)
            ->whereMonth('periode', $dari->format('m'))
            ->whereYear('periode', $dari->format('Y'))
            ->first();
            if(!$bulan)
            {
                $nominal += 105000;
                $jumlah += 1;
                $list[$dari->format('Y')][] = $dari->format('F Y');
            }
            $dari->addMonth(1);
        }
        $data = array(
            'jumlah' => $jumlah,
            'nominal' => currency($nominal),
            'list' => $list,
        );
        return $data;
    }

    public function last_simkop()
    {
        return $this->hasOne('Modules\Simpanan\Entities\SimkopTransaksi', 'anggota_id')->orderBy('periode', 'DESC')->latest();
    }


    public function getStatusBadgeAttribute($value)
    {
        if ($this->status === 1) {
            return '<span class="badge badge-success">Aktif</span>';
        } else {
            return '<span class="badge badge-danger">Keluar</span>';
        }
    }
}
