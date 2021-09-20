<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class TransaksiBayar extends Model
{
    protected $table = 'transaksi_bayar';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id', 'bank_id', 'program', 'amount', 'code', 'status', 'method'
    ];


    public function transaksi()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Transaksi');
    }


    public function bank()
    {
        return $this->belongsTo('Modules\Keuangan\Entities\Bank');
    }


    public function getMethodAttribute($value)
    {
        if($value == 'simla'){
            return 'Simpanan Sukarela';
        }else{
            return $value;
        }
    }




}
