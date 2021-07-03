<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'no_transaksi';

    protected $fillable = [
        'no_transaksi', 'anggota_id', 'teller_id', 'jenis', 'item', 'total'
    ];

    protected $appends = [
        'jenis_transaksi', 'darike'
    ];

    public function anggota(){
        return $this->belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id');
    }

    public function teller()
    {
        return $this->belongsTo('App\Models\Admin', 'teller_id', 'id');
    }

    public function simkop()
    {
        return $this->belongsTo('Modules\Simpanan\Entities\SimkopTransaksi', 'no_transaksi', 'no_transaksi');
    }

    public function simla()
    {
        return $this->belongsTo('Modules\Simpanan\Entities\SimlaTransaksi', 'no_transaksi', 'no_transaksi');
    }

    public function transaksi_kas()
    {
        return $this->hasMany('Modules\Keuangan\Entities\TransaksiKas', 'no_transaksi', 'no_transaksi');
    }

    public function getJenisTransaksiAttribute($value)
    {
        if($this->jenis == 'setoran wajib' || $this->jenis == 'setoran sukarela'){
            return 'Deposit';
        }elseif($this->jenis == 'penarikan sukarela'){
            return 'Withdrawal';
        }elseif($this->jenis == 'pendaftaran'){
            return 'Pendaftaran';
        }else{
            return '';
        }
    }

    public function getDarikeAttribute($value)
    {
        if($this->jenis == 'setoran wajib' || $this->jenis == 'setoran sukarela'){
            return 'Koperasi BUMABA';
        }elseif($this->jenis == 'penarikan sukarela'){
            return 'Koperasi BUMABA';
        }elseif($this->jenis == 'pendaftaran'){
            return 'Koperasi BUMABA';
        }else{
            return '';
        }
    }


}
