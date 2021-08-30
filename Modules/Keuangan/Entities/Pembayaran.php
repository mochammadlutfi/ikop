<?php

namespace Modules\Keuangan\Entities;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'transaksi_bayar';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id', 'jumlah', 'method', 'bank_id', 'status'
    ];

}
