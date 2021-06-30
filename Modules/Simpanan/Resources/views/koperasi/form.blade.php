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
                <input type="hidden" name="id" value="">
                <input type="hidden" id="method" value="store">
                <h2 class="content-heading pt-0">Informasi Anggota</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ID Anggota</label>
                            <select class="form-control" name="anggota_id" id="field-anggota_id"></select>
                            <span id="error-anggota_id" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label>No Identitas</label>
                            <input type="text" readonly="readonly" class="form-control" name="no_ktp">
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" readonly="readonly" class="form-control" name="nama">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" readonly="readonly" class="form-control" name="no_hp">
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" readonly="readonly" name="alamat" placeholder="Alamat Lengkap" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <h2 class="content-heading pt-0">Informasi Setoran</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. Transaksi</label>
                            <input type="text" class="form-control" name="kd_transaksi" value="{{ generate_transaksi_kd() }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Transaksi</label>
                            <input type="text" id="field-tgl" class="form-control" name="tgl" >
                            <span id="error-tgl" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label>Periode Bulan</label>
                            <input type="text" id="field-periode" class="form-control" name="periode">
                            <span id="error-periode" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-kas_id">Kas</label>
                            <select class="form-control" name="kas_id" id="field-kas_id"></select>
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
                                <tr>
                                    <td>Simpanan Wajib</td>
                                    <td>
                                        Rp <span class="display_currency">100000</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Simpanan Sosial</td>
                                    <td>
                                        <input type="number" class="form-control jml_sosial" id="field-jml_bayar" name="jml_sosial" value="5000">
                                        <span id="error-sosial" class="invalid-feedback"></span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th width="30%">TOTAL SETORAN</th>
                                    <th>
                                        <input type="hidden" id="input_total_setoran" name="total" value="105000">
                                        <div id="display_total_setoran">RP <span id="total_simpanan" class="display_currency">105000</span></div>
                                    </th>
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


