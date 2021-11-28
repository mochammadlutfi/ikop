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
            <a class="breadcrumb-item" href="{{ route('setoran.wajib') }}">Setoran Tunai Simpanan Wajib & Sosial</a>
        </nav>
    </div>
</div>

<div class="content">
    <form id="form-setor" onsubmit="return false">
        @csrf
        <div class="content-heading pt-0 mb-3">
            Setoran Tunai Simpanan Wajib & Sukarela
            <div class="float-right">
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="si si-paper-plane mr-1"></i> Simpan
                </button>
            </div>
        </div>
        
        <div class="block">
            <div class="block-content pb-15">
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
                                <input type="text" class="form-control" name="kd_transaksi" value="{{ get_simkop_nomor() }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Transaksi</label>
                                <input type="text" id="field-tgl" class="form-control" name="tgl" autocomplete="off">
                                <span id="error-tgl" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <input type="text" id="field-periode" class="form-control" name="periode" autocomplete="off">
                                <span id="error-periode" class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label>Keterangan (Optional)</label>
                                <input type="text" id="field-keterangan" class="form-control" name="keterangan" placeholder="Masukan Keterangan (Jika Ada)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="disabledDates" value="">
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
                                            <span class="pl-3 currency">100000</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Simpanan Sosial</td>
                                        <td>
                                            <input type="text" class="form-control input-currency" id="field-jml_sosial" name="jml_sosial" value="5000">
                                            <span id="error-sosial" class="invalid-feedback"></span>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th width="30%">TOTAL SETORAN</th>
                                        <th>
                                            <input type="text" class="form-control input-currency" readonly id="field-total" name="total" value="105000">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </form>
</div>
@stop
@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ asset('js/admin/setoran/wajib.js') }}"></script>
{{-- <script src="{{ Module::asset('Simpanan:Assets/simkop/form.js') }}"></script> --}}
@endpush


