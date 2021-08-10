@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css"/>
<link rel="stylesheet" href="https://raw.githubusercontent.com/flatpickr/flatpickr/master/src/plugins/monthSelect/style.css"/>
@endsection
@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('simla.riwayat') }}">Pembiayaan Tunai</a>
            <span class="breadcrumb-item active">Daftar Tagihan</span>
        </nav>
    </div>
</div>

<div class="content">
    <div class="content-heading pt-0 mb-3 row no-gutters">
        <div class="col">
            Daftar Tagihan
        </div>
        <div class="col-2">
            
            <div class="input-group" id="filter-month">
                <input type="text" placeholder="Select Date.." class="form-control" data-input readonly>
                <div class="input-group-append">
                    <span class="input-group-text input-button" title="toggle" data-toggle>
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            
            </div>
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
                        <div class="col-md-8 my-auto text-right" id="content-nav">
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
                        <th width="12%">No. Pembiayaan</th>
                        <th>Anggota</th>
                        <th>Jumlah Pembiayaan</th>
                        <th>Angsuran Ke</th>
                        <th>Jumlah Angsuran</th>
                        <th>Tempo</th>
                        <th class="text-center" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="loading" class="data-row d-none">
                        <td colspan="8">
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
@stop

@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>

{{-- <script src="https://unpkg.com/flatpickr@4.6.9/dist/plugins/monthSelect/monthSelect.js"></script> --}}
<script src="{{ Module::asset('Pembiayaan:Assets/tunai/tagihan.js') }}"></script>
@endpush
