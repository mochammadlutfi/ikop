@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection


@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('simla.riwayat') }}">Pembiayaan Darurat</a>
            <span class="breadcrumb-item active">Pengajuan</span>
        </nav>
    </div>
</div>

<div class="content">
    <form id="form-pengajuan" onsubmit="return false;">
        <div class="content-heading pt-0 mb-3">
            Buat Pengajuan
            <div class="float-right">
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="si si-paper-plane mr-1"></i> Simpan
                </button>
            </div>
        </div>
        <div class="block block-rounded block-shadow block-bordered mb-5">
            <div class="block-content">
                <div class="border-2x border-bottom mb-3 pb-2">
                    <h2 class="h5 mb-0 pt-0">Informasi Anggota</h2>
                </div>
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
                            <label>No. Ponsel</label>
                            <input type="text" readonly="readonly" class="form-control" name="no_hp">
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" readonly="readonly" name="alamat" placeholder="Alamat Lengkap" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="block-content block-content-full pt-0">
                <div class="border-2x border-bottom mb-3 pb-2">
                    <h2 class="h5 mb-0 pt-0">Informasi Pembiayaan</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label" for="field-jumlah">Jumlah Pembiayaan</label>
                            <input type="text" class="form-control input-currency" name="jumlah" id="field-jumlah" value="200000" min="200000" max="">
                            <span id="error-jumlah" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="field-tenor">Durasi Pembiayaan</label>
                            <select class="form-control" id="field-tenor" name="tenor">
                                <option value="2">2 Bulan</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="9">9 Bulan</option>
                                <option value="12">12 Bulan</option>
                            </select>
                            <span id="error-tenor" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label" for="field-biaya_admin">Biaya Admin (1.00%)</label>
                            <input type="text" class="form-control-plaintext input-currency" name="biaya_admin" id="field-biaya_admin" readonly>
                            <span id="error-biaya_admin" class="invalid-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="field-diterima">Jumlah yang Diterima</label>
                            <input type="text" class="form-control-plaintext input-currency" name="diterima" id="field-diterima" readonly>
                            <span id="error-diterima" class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="border-2x border-bottom mb-3 pb-2">
                    <h2 class="h5 mb-0 pt-0">Informasi Angsuran</h2>
                </div>
                <input type="hidden" name="jumlah_bunga" id="field-jumlah_bunga" value="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label" for="field-angsuran_pokok">Angsuran Pokok Bulanan</label>
                            <div class="col-lg-6">
                                <input type="text" class="font-w600 form-control-plaintext input-currency text-right" name="angsuran_pokok" id="field-angsuran_pokok" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label" for="field-angsuran_bunga">Angsuran Bunga Bulanan (3.95%)</label>
                            <div class="col-lg-6">
                                <input type="text" class="font-w600 form-control-plaintext input-currency text-right" name="angsuran_bunga" id="field-angsuran_bunga" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@stop



@push('scripts')
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ Module::asset('Pembiayaan:Assets/tunai/pengajuan-form.js') }}"></script>
@endpush
