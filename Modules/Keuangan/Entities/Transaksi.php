<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'no_transaksi', 'anggota_id', 'teller_id', 'jenis', 'item', 'total'
    ];

    protected $appends = [
        'darike', 'jenis_transaksi'
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
        return $this->hasOne('Modules\Simpanan\Entities\SimkopTransaksi');
    }

    public function simla()
    {
        return $this->hasOne('Modules\Simpanan\Entities\SimlaTransaksi');
    }

    public function ppob()
    {
        return $this->hasOne('Modules\PPOB\Entities\TransaksiPPOB');
    }

    public function pembayaran()
    {
        return $this->hasOne('Modules\Keuangan\Entities\TransaksiBayar')->withDefault([
            'status' => 'confirm',
        ]);
    }

    public function line()
    {
        return $this->hasMany('Modules\Keuangan\Entities\TransaksiLine');
    }

    public function getJenisTransaksiAttribute($value)
    {
        if($this->jenis == 'setoran wajib'){
            return 'Setoran';
        }elseif($this->jenis == 'penarikan sukarela'){
            return 'Penarikan';
        }elseif($this->jenis == 'pendaftaran'){
            return 'Pendaftaran';
        }else if($this->jenis == 'setoran sukarela'){
            return 'Isi Saldo';
        }else if($this->jenis == 'transfer sukarela'){
            return 'Transfer';
        }else if($this->jenis == 'pembelian'){
            // return 'Pembelian '.ucwords($this->service);
            if($this->sub_service == 'pulsa'){
                return 'Pembelian Pulsa';
            }elseif($this->sub_service == 'data'){
                return 'Pembelian Data';
            }
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
