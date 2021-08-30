@extends('layouts.master')
@section('styles')

<style>
    .table th,
    .table td {
        padding: 6px 10px !important;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Transaksi
        <div class="float-right">
            <a href="{{ route('simkop.invoice.print', $data->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                <i class="si si-printer mr-1"></i> Print
            </a>
            <a href="{{ route('simkop.edit', $data->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                <i class="si si-note mr-1"></i> Edit
            </a>
        </div>
    </div>
    <div class="block block-shadow block-rounded">
        <div class="bg-body-light block-content">
            <div class="row no-gutters py-15 ">
                <div class="col-lg-6 d-flex">
                    <div class="my-auto">
                        <h2 class="text-left font-w700 mb-0">SLIP SETORAN</h2>
                        <h5 class="text-left font-w700 mb-0">SIMPANAN KOPERASI</h5>
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
        <div class="block-content pt-3 px-3">
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
                    <span class="ml-3">: {{ Date::parse($data->tgl)->format('l d F Y') }}</span>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-lg-12">
                    <div class="py-5 px-5 bg-primary">
                        <span class="text-white">
                            <b>Harap ditulis dengan huruf cetak</b>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="box">
                        <p>
                            <b>Atas Transaksi Ini Dibebankan Ke Anggota</b>
                        </p>
                        <div class="form-group row mb-1">
                            <label class="col-6 col-lg-3 mb-0 font-size-16 font-size-14-down-lg">ID Anggota</label>
                            <div class="col-6 col-lg-9">
                                <div class="form-control-plaintext text-left py-0 font-size-16 font-size-14-down-lg">
                                    : {{ $data->anggota->anggota_id }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-6 col-lg-3 mb-0 font-size-16 font-size-14-down-lg">Nama</label>
                            <div class="col-6 col-lg-9">
                                <div class="form-control-plaintext text-left py-0 font-size-16 font-size-14-down-lg">
                                    : {{ $data->anggota->nama }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-6 col-lg-3 mb-0 font-size-16 font-size-14-down-lg">Alamat</label>
                            <div class="col-6 col-lg-9">
                                <div class="form-control-plaintext text-left py-0 font-size-16 font-size-14-down-lg">
                                    : {{ $data->anggota->alamat_full }}
                                </div>
                            </div>
                        </div>
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
                                <td class="text-center" style="width: 200px;">{{ $data->teller->anggota->nama }}</td>
                                <td class="text-center" style="width: 200px;">{{ $data->anggota->nama }}</td>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 200px;">Teller</th>
                                <th class="text-center" style="width: 200px;">Penyetor</th>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group row mb-1">
                        <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">No. Transaksi</label>
                        <div class="col-6 col-lg-9">
                            <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                : {{ $data->no_transaksi }}
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-vcenter my-2">
                        <thead>
                            <tr>
                                <th class="text-center" width="30">No</th>
                                <th class="text-center">Jenis Simpanan</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1 ;?>
                            {{-- @if (is_array($data->item) || is_object($data->item)) --}}
                            @foreach(json_decode($data->item) as $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-left">
                                    <p class="font-w600 mb-5">{{ $item->keterangan }}</p>
                                </td>
                                <td class="text-left">Rp {{ number_format($item->nominal,0,",",".") }}</td>
                            </tr>
                            @endforeach
                            {{-- @endif --}}
                            <tr class="table-warning">
                                <td></td>
                                <td class="font-w700 text-uppercase text-left">Total</td>
                                <td class="font-w700 text-left">Rp {{ number_format($data->total,0,",",".") }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group row mb-1">
                        <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Terbilang</label>
                        <div class="col-6 col-lg-9">
                            <div class="form-control-plaintext py-0 font-size-18 font-size-14-down-lg"
                                style="border-bottom: 1px dashed;">
                                : {{ ucwords(terbilang($data->total)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Invoice Info -->

            <!-- Footer -->
            <p class="text-muted text-center">Transaksi ini sah apabila slip bukti setoran di validasi dan dibutuhi
                tanda
                tangan teller</p>
            <!-- END Footer -->
        </div>
    </div>
    <!-- END Invoice -->
</div>
@stop
@push('scripts')

@endpush
