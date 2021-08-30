@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<style>
    #list-rekening_filter {
        display: none;
    }
</style>
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Rekening Bank
        <button type="button" class="btn btn-primary float-right" id="btn-add_rekening">Tambah Rekening</button>
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
    <div class="block block-rounded block-shadow block-bordered mb-5">
        <div class="block-content px-0 py-0">
            <input type="hidden" id="current_page" value="1">
            <table class="table table-striped table-vcenter table-hover mb-0" id="data-list">
                <thead class="thead-light">
                    <tr>
                        <th colspan="2">Bank</th>
                        <th>Kode</th>
                        <th>No Rekening</th>
                        <th>Atas Nama</th>
                        <th>Aksi</th>
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

<div class="modal" id="modalRekening">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="return false" id="form-bank">
                @csrf
                <input type="hidden" name="id" id="field-id" value="">
                <input type="hidden" name="method" id="method" value="store">
                <div class="block-header block-header-default">
                    <h3 class="block-title modal-title">Judul Modal</h3>
                    <div class="block-options">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="fa fa-times-circle"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="field-nama">Nama Bank</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="field-nama" name="nama" placeholder="Masukan Nama Bank" autocomplete="off">
                            <div class="invalid-feedback font-size-sm" id="error-nama">Invalid feedback</div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="field-kode">Kode Bank</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="field-kode" name="kode" placeholder="Masukan Kode Bank" autocomplete="off">
                            <div class="invalid-feedback font-size-sm" id="error-kode">Invalid feedback</div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="field-rekening">No Rekening</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="field-rekening" name="rekening" placeholder="Masukan Rekening Bank" autocomplete="off">
                            <div class="invalid-feedback font-size-sm" id="error-rekening">Invalid feedback</div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="field-atas_nama">Atas Nama</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="field-atas_nama" name="atas_nama" placeholder="Masukan Atas Nama Bank" autocomplete="off">
                            <div class="invalid-feedback font-size-sm" id="error-atas_nama">Invalid feedback</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="field-icon">Icon</label>
                        <div class="col-lg-7">
                            <img id="thumbPrev" class="border border-2x border-primary img-fluid mb-2" src="https://via.placeholder.com/128x64.png?text=ICON+BANK"/>
                            <div class="btn btn-primary">
                                <input type="file" class="file-upload" id="uploadThumb" accept="image/*" name="icon">
                                <i class="si si-camera mr-1"></i>Pilih Icon
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Keuangan:Assets/js/bank.js') }}"></script>
@endpush
