@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection


@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="#">Pembiayaan Tunai</a>
            <span class="breadcrumb-item active">Pengajuan</span>
        </nav>
    </div>
</div>

<div class="content">
    <div class="content-heading pt-0 mb-3">
        Daftar Pengajuan
        <div class="float-right">
            <a type="button" class="btn btn-sm btn-secondary" href="{{ route('pmb_tunai.pengajuan.create') }}">
                <i class="si si-plus mr-1"></i> Buat Pengajuan
            </a>
        </div>
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
                        <div class="col-md-8 m-auto" id="content-nav">
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
    <div class="block block-rounded block-shadow-2 block-bordered mb-5">
        <div class="block-content px-0 py-0">
            <input type="hidden" id="current_page" value="1">
            <table class="table table-striped table-vcenter table-hover mb-0 font-size-sm" id="data-list">
                <thead class="thead-light">
                    <tr>
                        <th width="15%">Tgl Pengajuan</th>
                        <th width="12%">No. Pembiayaan</th>
                        <th>Anggota</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Durasi</th>
                        <th width="5%">Status</th>
                        <th width="10%" class="text-center">Aksi</th>
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

<div class="modal" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header border-3x border-bottom">
                    <h3 class="block-title">Detail Pembiayaan</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="id" id="field-id" value="">
                <div class="block-content" id="detail">
                </div>
                <div class="block-content">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Angsuran Pokok</th>
                                <th>Angsuran Bagi Hasil</th>
                                <th>Jumlah Angsuran</th>
                                <th>Tempo</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer p-2"></div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ Module::asset('Pembiayaan:Assets/tunai/pengajuan.js') }}"></script>
@endpush
