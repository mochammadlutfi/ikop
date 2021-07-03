<?php


use Modules\Simpanan\Entities\SimkopTransaksi;
use Carbon\Carbon;
/**
 * Generate ID Anggota
 * Rumus : Kode Cabang + Tahun + Bulan + No Urut
 * @return Renderable
 */
if(!function_exists('currency')){
    function currency($value){
        return "Rp " .number_format($value,0,',','.');
    }
}


// if(!function_exists('money')){
//     function currency($value){
//         return number_format($value,0,',','.');
//     }
// }


