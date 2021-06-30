@extends('layouts.master')

@section('styles')
<style>
 .box {
    border-left:1px solid black;
    margin-bottom: 3px;
    border-bottom:1px solid black;
 }
 .box, p {
     padding:0 3px;
     margin-bottom: 5px !important;
 }
</style>
@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Transaksi
    </div>
    <div class="block block-shadow block-rounded">
        <div class="bg-body-light block-content">
            <div class="row no-gutters py-15 ">
                <div class="col-lg-6 d-flex">
                    <div class="my-auto">
                        <h2 class="text-left font-w700 mb-0">SLIP SETORAN</h2>
                        <h5 class="text-left font-w700 mb-0">SIMPANAN SUKARELA</h5>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="float-right">
                        <img src="{{ asset('media/logo/logo_slip.png') }}" class="img-fluid height-75">
                        <img src="{{ asset('media/logo/logo_koperasi.png') }}" class="img-fluid height-75">
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content pt-3 px-3 pb-15">
            <div class="row my-5">
                <div class="col-lg-12">
                    <span>Bismillahirrahmanirrahim</span>
                </div>
                <div class="col-lg-6">
                    <b>Cabang</b>
                    <span class="ml-3">: Kantor Utama</span>
                </div>
                <div class="col-lg-6">
                    <b>Tanggal</b>
                    <span class="ml-3">: {{ Date::parse($invoice->tgl)->format('l d F Y') }}</span>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-lg-12">
                    <div style="background-color:#007a37" class="py-5 px-5">
                        <span class="text-white">
                            <b>Harap ditulis dengan huruf cetak</b>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="box">
                        <p><b>Validasi</b>
                            <br>
                            <br>
                            <br>
                            <br>
                        </p>
                    </div>
                    <div class="box">
                        <p><b>Atas Transaksi Ini Dibebankan Ke Anggota</b></p>
                        <table style="width:100%">
                            <tr>
                                <td width="25%"><b>Nomor Anggota</b></td>
                                <td>: {{ $invoice->anggota->anggota_id }}</td>
                            </tr>
                            <tr>
                                <td><b>Nama Anggota</b></td>
                                <td>: {{ $invoice->anggota->nama }}</td>
                            </tr>
                            <tr>
                                <td><b>Jenis Penarikan</b></td>
                                <td>: {{ ucwords($invoice->jenis) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box">
                        <table style="width:100%">
                            <tr>
                                <td width="25%"><b>Jumlah</b></td>
                                <td>: Rp <span class="display_currency">{{ abs($invoice->total) }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="box">
                        <table style="width:100%">
                            <tr>
                                <td width="25%"><b>Terbilang</b></td>
                                <td>: {{ ucwords(terbilang($invoice->total)) }} Rupiah
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="box">
                        <table style="width: 100%;">
                            <tr>
                                <td><br></td>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td> <br></td>
                            </tr>
                            <tr>
                                <td> <br></td>
                                <td><br> </td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 200px;">----------------------</td>
                                <td class="text-center" style="width: 200px;">----------------------</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 200px;">{{ $invoice->teller->anggota->nama }}</td>
                                <td class="text-center" style="width: 200px;">{{ $invoice->anggota->nama }}</td>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 200px;">Teller</th>
                                <th class="text-center" style="width: 200px;">Penyetor</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END Invoice Info -->

            <!-- Footer -->
            <p class="text-muted text-center">Dengan ini, saya menginstruksikan Koperasi untuk melakukan transaksi sesuai data tersebut</p>
            <!-- END Footer -->
        </div>
    </div>
    <!-- END Invoice -->
</div>
@endsection
