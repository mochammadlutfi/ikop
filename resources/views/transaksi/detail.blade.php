@extends('layouts.master')

@section('content')

<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Transaksi
        <div class="float-right">

        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-body-light block-content">
                <div class="justify-content-sm-between no-gutters py-15 row">
                    <div class="col-lg-6 d-flex ">
                        <div class="my-auto">
                            <h2 class="font-w700 mb-0">BUKTI TRANSAKSI</h2>
                        </div>
                    </div>
                    <div class="col col-6 text-right">
                        <img src="{{ asset('media/logo/logo_slip.png') }}" height="60px">
                        <img src="{{ asset('media/logo/logo_koperasi.png') }}" height="60px">
                    </div>
                </div>
            </div>
            <div class="block block-rounded block-shadow block-bordered d-md-block d-none mb-10">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-4"><b>No Transaksi</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">{{ $data->nomor }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Tanggal Transaksi</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">{{ Date::parse($data->tgl)->format('d F Y') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Jenis Transaksi</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">{{ $data->jenis_transaksi }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Metode Pembayaran</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">{{ $data->pembayaran->method }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4"><b>ID Anggota</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">{{ $data->anggota->anggota_id }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Nama</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">{{ $data->anggota->nama }}</div>
                    </div>
                    @foreach($line as $item)
                    <div class="row">
                        <div class="col-4"><b>{{ $item->keterangan }}</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">Rp {{ number_format($item->jumlah,0,",",".") }}</div>
                    </div>
                    @endforeach
                    @if($data->pembayaran->code != null)
                    <div class="row">
                        <div class="col-4"><b>Kode Unik</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">Rp {{ number_format($data->pembayaran->code,0,",",".") }}</div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-4"><b>Total Transaksi</b><span class="float-right">:</span></div>
                        <div class="col-8 px-0 text-right">Rp {{ number_format($data->total,0,",",".") }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
