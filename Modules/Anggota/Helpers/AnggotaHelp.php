<?php

use Modules\Anggota\Entities\Anggota;

    /**
     * Generate ID Anggota
     * Rumus : Kode Cabang + Tahun + Bulan + No Urut
     * @return Renderable
     */
    if(!function_exists('generate_anggota_id')){
        function generate_anggota_id(){
            $q = Anggota::select(DB::raw('MAX(RIGHT(anggota_id,4)) AS kd_max'));
            $urut = "";

            $kd_cabang = 1;
            $no = 1;
            date_default_timezone_set('Asia/Jakarta');

            if($q->count() > 0){
                foreach($q->get() as $k){
                    return $kd_cabang . date('ym').sprintf("%04s", abs(((int)$k->kd_max) + 1));
                }
            }else{
                return $kd_cabang . date('ym').sprintf("%04s", $no);
            }
        }
    }