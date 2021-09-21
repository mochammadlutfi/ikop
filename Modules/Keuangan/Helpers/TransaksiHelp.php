<?php

use Modules\Keuangan\Entities\Transaksi;
use Modules\Keuangan\Entities\TransaksiKas;
use Modules\Pembiayaan\Entities\PmbTunai;
use Modules\Keuangan\Entities\TransaksiBayar;
use Modules\PPOB\Entities\TransaksiPPOB;

/**
 * Generate ID Anggota
 * Rumus : Kode Cabang + Tahun + Bulan + No Urut
 * @return Renderable
 */
if(!function_exists('get_ppob_code')){
    function get_ppob_code(){
        $date = Date::now()->format('Y-m-d');
        $q = TransaksiPPOB::select(DB::raw('MAX(code) AS kd_max'))->whereDate('created_at', $date);
        
        date_default_timezone_set('Asia/Jakarta');
        $code = 'PB/';
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code. Date::now()->format('ymd') .'/'. sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code. Date::now()->format('ymd').'/'. sprintf("%05s", 1);
        }
    }
}


if(!function_exists('get_simla_nomor')){
    function get_simla_nomor(){
        $date = Date::now()->format('Y-m-d');
        $q = Transaksi::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'))->whereDate('tgl', $date)->where('sub_service', 'sukarela');
        
        $kd_cabang = 1;
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return 'SL/'. Date::now()->format('ymd') .'/'. sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return 'SL/'. Date::now()->format('ymd').'/'. sprintf("%05s", 1);
        }
    }
}

if(!function_exists('get_simkop_nomor')){
    function get_simkop_nomor(){
        $q = Transaksi::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));
        
        $kd_cabang = 1;
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return 'SK/'. Date::now()->format('ymd') .'/'. sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return 'SK/'. Date::now()->format('ymd').'/'. sprintf("%05s", 1);
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

if(!function_exists('get_payment_code')){
    function get_payment_code($tgl){
        $date = Date::parse($tgl)->format('Y-m-d');
        $q = TransaksiBayar::select(DB::raw('MAX(code) AS kd_max'))->whereDate('tgl_bayar', $date);
        
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return (int)$k->kd_max;
            }
        }else{
            return 100;
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

if(!function_exists('generate_pembiayaan_no')){
    function generate_pembiayaan_no($tipe){
        if($tipe == 'tunai'){
            $kd = 'PBT/';
            $q = PmbTunai::select(DB::raw('MAX(RIGHT(no_pembiayaan,5)) AS nomor_max'));
        }


        $no = 1;
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $kd . date('ymd') .'/'. sprintf("%05s", abs(((int)$k->nomor_max) + 1));
            }
        }else{
            return $kd . date('ymd') .'/'. sprintf("%05s", $no);
        }
    }
}
