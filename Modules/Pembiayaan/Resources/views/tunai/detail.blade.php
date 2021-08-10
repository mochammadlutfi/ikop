@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection


@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('simla.riwayat') }}">Pembiayaan Tunai</a>
            <span class="breadcrumb-item active">Pengajuan</span>
        </nav>
    </div>
</div>

<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Pembiayaan
        @if($data->status == 1)
        <span class="badge badge-info">Aktif</span>
        @elseif ($data->status == 2)
        <span class="badge badge-success">Lunas</span>
        @endif
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-secondary btn-bayar d-none">
                <i class="far fa-money-bill-alt mr-1"></i> Bayar Angsuran
            </button>
        </div>
    </div>
    <div class="block block-rounded block-shadow block-bordered mb-5">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="h5 mb-0 pt-0">Informasi Anggota</h2>
                    <hr class="border-2x">
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">ID Anggota</div>
                        <div class="col-8">: {{ $data->anggota->anggota_id }}</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">Nama Anggota</div>
                        <div class="col-8">: {{ $data->anggota->nama }}</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">No Ponsel</div>
                        <div class="col-8">: {{ $data->anggota->no_hp }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="h5 mb-0 pt-0">Informasi Pembiayaan</h2>
                    <hr class="border-2x">
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">No Pembiayaan</div>
                        <div class="col-8">: {{ $data->anggota->anggota_id }}</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">Tanggal Pengajuan</div>
                        <div class="col-8">: {{ Date::parse($data->created_at)->format('d F Y') }}</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">Jumlah Pembiayaan</div>
                        <div class="col-8">: <span class="currency"> {{ $data->jumlah }}</span></div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">Durasi Pembiayaan</div>
                        <div class="col-8">: {{ $data->durasi }} Bulan</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">Biaya Admin</div>
                        <div class="col-8">: <span class="currency"> {{ $data->biaya_admin }}</span></div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-4 font-w600">Jumlah Bagi Hasil</div>
                        <div class="col-8">: <span class="currency"> {{ $data->jumlah_bunga }}</span></div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-sm mt-15">
                <thead>
                    <tr>
                        <th class="text-center">
                            <div class="custom-checkbox custom-control m-auto">
                                <input class="custom-control-input" type="checkbox" id="select-all" value="option2">
                                <label class="custom-control-label" for="select-all"></label>
                            </div>
                        </th>
                        <th>No</th>
                        <th>Angsuran Pokok</th>
                        <th>Angsuran Bagi Hasil</th>
                        <th>Jumlah Angsuran</th>
                        <th>Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                    <tr class="angsuran-item">
                        <td class="text-center">
                            <input type="hidden" class="angsuran_ke" value="{{ $d->angsuran_ke }}/{{ $data->durasi }}">
                            <input type="hidden" class="angsuran_id" value="{{ $d->id }}">
                            <input type="hidden" class="jumlah" value="{{ $d->total }}">
                            <input type="hidden" class="tempo" value="{{ Date::parse($d->tgl_tempo)->format('d F Y') }}">
                            <div class="custom-checkbox custom-control m-auto">
                                <input class="custom-control-input" type="checkbox" name="{{ $d->id }}" id="{{ $d->id }}" value="{{ $d->id }}" {{ $d->status == 1 ? 'disabled="disabled"' : '' }}>
                                <label class="custom-control-label" for="{{ $d->id }}"></label>
                            </div>
                        </td>
                        <td>{{ $d->angsuran_ke }}/{{ $data->durasi }}</td>
                        <td><div class="currency">{{ $d->jumlah_pokok }}</div></td>
                        <td><div class="currency">{{ $d->jumlah_bunga }}</div></td>
                        <td><div class="currency">{{ $d->total }}</div></td>
                        <td>{{ Date::parse($d->tgl_tempo)->format('d F Y') }}</td>
                        <td>
                           @if($d->status == 1)
                            <span class="badge badge-success">Lunas</span>
                           @else
                            <span class="badge badge-warning">Belum Bayar</span>
                           @endif
                        </td>
                    <tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('pembiayaan::tunai.bayar')
@stop

@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ Module::asset('Pembiayaan:Assets/tunai/pembiayaan.js') }}"></script>
@endpush
