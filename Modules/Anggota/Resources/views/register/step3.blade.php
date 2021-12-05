@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Registrasi Anggota Baru
    </div>

    <div class="block mb-lg-1">
        <div class="block-content p-0">
            <!-- Step Tabs -->
            <ul class="nav nav-tabs nav-tabs-alt nav-fill nav-tabs-block">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('anggota.tambah') }}">1. Data Pribadi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('anggota.tambah.step2') }}">2. Data Alamat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('anggota.tambah.step3') }}">3. Pembayaran</a>
                </li>
            </ul>
            <!-- END Step Tabs -->
        </div>
    </div>


    <div class="block block-shadow block-bordered block-rounded">
        <!-- Form -->
        <form id="form-step3" onsubmit="return false;">
            <input type="hidden" name="method" id="method" value="create">
            <!-- Steps Content -->
            <div class="block-content block-content-full">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label>No. Transaksi</label>
                                        <input type="text" class="form-control" name="no_invoice" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-lg-12">
                                    <label for="field-tgl_payment">Tanggal Transaksi</label>
                                    <input type="text" class="form-control" id="field-tgl_payment" name="tgl_payment" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label>Keterangan (Optional)</label>
                                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Masukan Keterangan (Jika Ada)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-vcenter">
                                    <thead>
                                        <tr>
                                            <th width="30%">Jenis Pembayaran</th>
                                            <th width="20%">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Administrasi Pendaftaran</td>
                                            <td>
                                                <div class="currency">25000</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Simpanan Pokok</td>
                                            <td>
                                                <div class="form-control-plaintext">
                                                    <div class="currency">200000</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Simpanan Wajib</td>
                                            <td>
                                                <div class="form-control-plaintext">
                                                    <div class="currency">100000</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Simpanan Sosial</td>
                                            <td>
                                                <div class="form-control-plaintext">
                                                    <div class="currency">5000</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Simpanan Sukarela</td>
                                            <td>
                                                <input type="text" id="field-simla" class="form-control input-currency" name="simla" autocomplete="off">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th width="30%">TOTAL SETORAN</th>
                                            <th>
                                                <div class="form-control-plaintext">
                                                    <div class="currency" id="total_setoran">100000</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Steps Content -->

            <!-- Steps Navigation -->
            <div class="block-content block-content-sm block-content-full bg-body-light">
                <div class="row">
                    <div class="col-6">
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-alt-secondary" data-wizard="next">
                            Selanjutnya 
                            <i class="fa fa-angle-right ml-5"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- END Steps Navigation -->
        </form>
        <!-- END Form -->
    </div>
</div>
@stop
@push('scripts')
    
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Anggota:Assets/js/form.js') }}"></script>
@endpush