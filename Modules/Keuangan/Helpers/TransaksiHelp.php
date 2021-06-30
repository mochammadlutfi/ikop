<?php

use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;

/**
 * Generate ID Anggota
 * Rumus : Kode Cabang + Tahun + Bulan + No Urut
 * @return Renderable
 */
if(!function_exists('generate_transaksi_kd')){
    function generate_transaksi_kd(){
        $q = Transaksi::select(DB::raw('MAX(RIGHT(no_transaksi,5)) AS kd_max'));
        
        $kd_cabang = 1;
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $kd_cabang . date('ymd') . sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $kd_cabang . date('ymd') . sprintf("%05s", $no);
        }
    }
}

/**
 * Generate Kode Transaksi Kas
 * Rumus : Kode Transkasi + Kode Cabang + Tahun + Bulan + No Urut
 * @return Renderable
 */
if(!function_exists('get_no_transaksi_kas')){
    function get_no_transaksi_kas($jenis){
        if($jenis == 'pemasukan')
        {
            $kd_trans = 'KI';
        }elseif($jenis == 'pengeluaran')
        {
            $kd_trans = 'KO';
        }elseif($jenis == 'transfer')
        {
            $kd_trans = 'KT';
        }
        #KI = Kas Income
        $q = TransaksiKas::select(DB::raw('MAX(RIGHT(kd_trans_kas,5)) AS kd_max'));
        $no = 1;
        $kode_cabang = 1;
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $kd_trans. $kode_cabang .date('ymd').sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $kd_trans. $kode_cabang .date('ymd').sprintf("%05s", $no);
        }
    }
}


/**
 * Generate Kode Transaksi Kas
 * Rumus : Kode Transkasi + Kode Cabang + Tahun + Bulan + No Urut
 * @return Renderable
 */
if(!function_exists('terbilang')){
    function terbilang($nilai){
        $nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = terbilang($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = terbilang($nilai/10)." puluh". terbilang($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . terbilang($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = terbilang($nilai/100) . " ratus" . terbilang($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . terbilang($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = terbilang($nilai/1000) . " ribu" . terbilang($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = terbilang($nilai/1000000) . " juta" . terbilang($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = terbilang($nilai/1000000000) . " milyar" . terbilang(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = terbilang($nilai/1000000000000) . " trilyun" . terbilang(fmod($nilai,1000000000000));
		}
		return $temp;
    }
}
