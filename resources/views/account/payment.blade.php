@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection


@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('pembayaran') }}">Pembayaran</a>
        </nav>
    </div>
</div>

<div class="content">
    <div class="content-heading pt-0 mb-3">
        Daftar Pembayaran
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-secondary" id="tgl_range">
                <i class="fa fa-calendar"></i>
                <span></span>
                <input type="hidden" id="tgl_mulai" value="">
                <input type="hidden" id="tgl_akhir" value="">
            </button>
        </div>
    </div>
    <div class="block block-rounded mb-10">
        <input type="hidden" id="filter-status" value="aktif">
        <ul class="nav nav-tabs nav-fill nav-tabs-alt">
            <li class="nav-item">
                <a class="nav-link filter-status active" href="javascript:void(0)" data-status="aktif">Aktif</a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-status" href="javascript:void(0)" data-status="selesai">Selesai</a>
            </li>
        </ul>
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
                        <div class="col-md-8 m-auto text-right" id="content-nav">
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
                        <th width="15%">Tanggal</th>
                        <th width="12%">No. Transaksi</th>
                        <th>Anggota</th>
                        <th width="15%">Metode</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="block mb-0">
                <div class="block-header border-3x border-bottom">
                    <h3 class="block-title">Detail Pembayaran</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="id" id="field-id" value="">
                <div class="block-content" id="detail">
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
<script src="{{ asset('js/account/payment.js') }}"></script>
@endpush
