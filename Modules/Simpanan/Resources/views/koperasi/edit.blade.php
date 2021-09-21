@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Setoran Tunai
    </div>
    
    <div class="block">
        <div class="block-content pb-15">
            <form id="form-setor" onsubmit="return false">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id }}">
                <input type="hidden" id="method" value="update">
                <h2 class="content-heading pt-0">Informasi Anggota</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ID Anggota</label>
                            <select class="form-control" name="anggota_id" id="field-anggota_id" data-id="{{ $data->anggota->anggota_id }}" data-text="{{ $data->anggota->anggota_id }} - {{ $data->anggota->nama }}"></select>
                            <span id="error-anggota_id" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label>No Identitas</label>
                            <input type="text" readonly="readonly" class="form-control" name="no_ktp" value="{{ $data->anggota->no_ktp }}">
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" readonly="readonly" class="form-control" name="nama" value="{{ $data->anggota->nama }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" readonly="readonly" class="form-control" name="no_hp" value="{{ $data->anggota->no_hp }}">
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" readonly="readonly" name="alamat" placeholder="Alamat Lengkap" rows="5">{{ $data->anggota->alamat_full }}</textarea>
                        </div>
                    </div>
                </div>
                <h2 class="content-heading pt-0">Informasi Setoran</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. Transaksi</label>
                            <input type="text" class="form-control" name="kd_transaksi" value="{{ $data->nomor }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Transaksi</label>
                            <input type="text" id="field-tgl" class="form-control" name="tgl" value="{{ \Carbon\Carbon::parse($data->tgl)->format('d-m-Y') }}">
                            <span id="error-tgl" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label>Periode Bulan</label>
                            <input type="text" id="field-periode" class="form-control" name="periode" value="{{ Date::parse($data->simkop->periode)->format('F Y') }}">
                            <span id="error-periode" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-kas_id">Kas</label>
                            <select class="form-control" name="kas_id" id="field-kas_id" data-id="{{ $data->kas_id }}" data-text="{{ $data->kas_nama }}"></select>
                            <span id="error-kas_id" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label>Keterangan (Optional)</label>
                            <textarea class="form-control" name="keterangan" rows="5" placeholder="Masukan Keterangan (Jika Ada)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%">Jenis Simpanan</th>
                                    <th width="20%">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->line as $item)
                                <tr>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        @if($item->akun_id == 9)
                                            <input type="text" class="form-control input-currency" id="field-jml_sosial" name="jml_sosial" value="{{ $item->jumlah }}">
                                            <span id="error-sosial" class="invalid-feedback"></span>
                                        @else
                                            <span class="currency">{{ (int)$item->jumlah }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @if($data->line->count() > 1)
                                <tr>
                                    <td>Simpanan Sosial</td>
                                    <td>
                                        <input type="text" class="form-control input-currency" id="field-jml_sosial" name="jml_sosial" value="5000">
                                        <span id="error-sosial" class="invalid-feedback"></span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <tr>
                                        <th width="30%">TOTAL SETORAN</th>
                                        <th>
                                            <input type="text" class="form-control input-currency" readonly id="field-total" name="total" value="{{ $data->total }}">
                                        </th>
                                    </tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <button id="submit" type="submit" class="btn btn-primary">Proses Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Simpanan:Assets/simkop/form.js') }}"></script>
@endpush


