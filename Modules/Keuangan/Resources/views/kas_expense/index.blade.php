@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Kelola Pengeluaran Kas
        <button id="btn-add" type="button" class="btn btn-secondary mr-5 mb-5 float-right btn-rounded">
            <i class="si si-plus mr-5"></i>
            Tambah Pengeluaran Kas Baru
        </button>
    </div>

    <div class="block block-rounded block-shadow block-bordered d-md-block d-none mb-10">
        <div class="block-content p-2">
            <div class="row justify-content-between">
                <div class="col-4">
                    <div class="has-search">
                        <i class="fa fa-search"></i>
                        <input type="search" class="form-control" id="search-data-list" name="keyword">
                    </div>
                </div>
                <div class="col-4">
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-md-8 m-auto px-0" id="content-nav">
                            <span>Navigasi</span>
                        </div>
                        <div class="col-md-4 pt-25 pl-0">
                            <button type="button" class="btn btn-alt-secondary float-right" id="next-data-list">
                                <i class="fa fa-chevron-right fa-fw"></i>
                            </button>
                            <button type="button" class="btn btn-alt-secondary float-left" id="prev-data-list">
                                <i class="fa fa-chevron-left fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-rounded block-shadow block-bordered mb-5">
        <div class="block-content px-0 py-0">
            <input type="hidden" id="current_page" value="1">
            <table class="table table-striped table-vcenter table-hover mb-0 v-center font-size-sm" id="data-list">
                <thead class="thead-light">
                    <tr>
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Dari Kas</th>
                        <th>Untuk Akun</th>
                        <th>Jumlah</th>
                        <th>Pengguna</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="loading" class="data-row d-none">
                        <td colspan="7">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 text-center py-50">
                                    <div class="spinner-border text-primary wh-50" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('keuangan::kas_expense.form')

@stop
@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Keuangan:Assets/js/kas_expense.js') }}"></script>
@endpush
