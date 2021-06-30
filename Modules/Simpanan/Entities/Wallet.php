<?php

namespace Modules\Simpanan\Entities;


use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallet';
    protected $primaryKey = 'anggota_id';

    protected $increments = false;

    protected $fillable = [
        'anggota_id', 'wajib', 'pokok', 'sukarela', 'sosial'
    ];

    public function anggota(){

        return belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id');
    }


    public function tujuan(){
        return belongsTo('Modules\Anggota\Entities\Anggota', 'anggota_id');
    }


}
