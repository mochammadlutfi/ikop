@extends('layouts.master')
@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Kelola Kas
        <button id="btn-add" type="button" class="btn btn-secondary mr-5 mb-5 float-right btn-rounded">
            <i class="si si-plus mr-5"></i>
            Tambah Kas Baru
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
                        <th>No</th>
                        <th>Nama Kas</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Transfer</th>
                        <th>Status</th>
                        <th></th>
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


<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded">
            <div class="block block-transparent mb-0">
                <form id="form-kas" onsubmit="return false">
                    @csrf
                    <input type="hidden" id="field-id" value="" name="id"/>
                    <input type="hidden" value="add" id="method"/>
                    <div class="block-header bg-alt-secondary">
                        <h3 class="block-title" id="modal_title">Form Title</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Kas</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-nama" class="form-control" name="nama" placeholder="Nama Kas">
                                <div class="text-danger font-size-sm" id="error-nama"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Simpanan</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="simpanan" id="simpanan1" value="1">
                                    <label class="custom-control-label" for="simpanan1">Ya</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="simpanan" id="simpanan0" value="0">
                                    <label class="custom-control-label" for="simpanan0">Tidak</label>
                                </div>
                                <div class="text-danger font-size-sm" id="error-simpanan"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pengeluaran</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="pengeluaran" id="pengeluaran1" value="1">
                                    <label class="custom-control-label" for="pengeluaran1">Ya</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="pengeluaran" id="pengeluaran0" value="0">
                                    <label class="custom-control-label" for="pengeluaran0">Tidak</label>
                                </div>
                                <div class="text-danger font-size-sm" id="error-pengeluaran"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Transfer</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="transfer" id="transfer1" value="1">
                                    <label class="custom-control-label" for="transfer1">Ya</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="transfer" id="transfer0" value="0">
                                    <label class="custom-control-label" for="transfer0">Tidak</label>
                                </div>
                                <div class="text-danger font-size-sm" id="error-transfer"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Status</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status" id="status1" value="1">
                                    <label class="custom-control-label" for="status1">Aktif</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status" id="status0" value="0">
                                    <label class="custom-control-label" for="status0">Tidak</label>
                                </div>
                                <div class="text-danger font-size-sm" id="error-status"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-times-circle"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
@push('scripts')
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Keuangan:Assets/js/kas.js') }}"></script>
@endpush
