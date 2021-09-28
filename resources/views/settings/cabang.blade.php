@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Kelola Kantor Cabang
        <button id="btn-add" type="button" class="btn btn-secondary mr-5 mb-5 float-right btn-rounded">
            <i class="si si-plus mr-5"></i>
            Tambah Kantor Cabang Baru
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
                        <th width="2%">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="example-checkbox1" id="example-checkbox1" value="option1">
                                <label class="custom-control-label" for="example-checkbox1"></label>
                            </div>
                        </th>
                        <th width="5%">Kode</th>
                        <th width="8%">Nama</th>
                        <th width="15%">Wilayah</th>
                        <th width="15%">Alamat</th>
                        <th width="9%">Aksi</th>
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
                <form id="form-cabang" onsubmit="return false">
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
                        <div class="form-group">
                            <label for="field-nama">Nama Cabang</label>
                            <input type="text" class="form-control" id="field-nama" name="nama">
                            <div class="invalid-feedback" id="error-nama">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label for="field-wilayah">Wilayah</label>
                            <select class="form-control" name="wilayah" id="field-wilayah"></select>
                            <div class="invalid-feedback" id="error-wilayah">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label for="field-kode_pos">Kode POS</label>
                            <input type="text" class="form-control" id="field-kode_pos" name="kode_pos">
                            <div class="invalid-feedback" id="error-kode_pos">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label for="field-alamat">Alamat Lengkap</label>
                            <textarea class="form-control" id="field-alamat" name="alamat" rows="3"></textarea>
                            <div class="invalid-feedback" id="error-alamat">Invalid feedback</div>
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
<script src="{{ asset('js/settings/cabang.js') }}"></script>
<script src="{{ Module::asset('Cabang:Assets/js/index.js') }}"></script>
<script>
</script>
@endpush
