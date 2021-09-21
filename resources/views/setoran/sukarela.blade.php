@extends('layouts.master')


@section('styles')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
@endsection

@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('simla.riwayat') }}">Simpanan Sukarela</a>
            <span class="breadcrumb-item active">Setoran Tunai</span>
        </nav>
    </div>
</div>

<div class="content">
    <div class="content-heading pt-0 mb-3">
        Setoran Tunai
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="block-content pb-15">
                    <form id="form-simla" method="POST" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
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
                                    <label>No. Ponsel</label>
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
                                    <label for="field-kd_transaksi">No. Transaksi</label>
                                    <input type="text" id="field-transaksi" class="form-control" name="kd_transaksi" value="{{ get_simla_nomor() }}">
                                </div>
                                <div class="form-group">
                                    <label for="field-tgl">Tanggal Transaksi</label>
                                    <input type="text" id="field-tgl" class="form-control" name="tgl" autocomplete="off" value="{{ Date::today()->format('d-m-Y') }}">
                                    <span id="error-tgl" class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="field-jumlah">Jumlah</label>
                                    <input type="text" class="form-control input-currency" name="jumlah" id="field-jumlah" autocomplete="off">
                                    <span id="error-jumlah" class="invalid-feedback"></span>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan (Optional)</label>
                                    <input type="text" class="form-control" name="keterangan" id="field-keterangan" placeholder="Masukan Keterangan (Jika Ada)">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button id="submit" type="submit" class="btn btn-primary">Proses Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ Module::asset('Simpanan:Assets/simla/form.js') }}"></script>
@endpush
