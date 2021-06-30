@extends('layouts.master')

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Tunggakan Pembayaran
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
    <div class="block block-rounded block-shadow block-bordered mb-5">
        <div class="block-content px-0 py-0">
            <input type="hidden" id="current_page" value="1">
            <table class="table table-striped table-vcenter table-hover mb-0" id="data-list">
                <thead class="thead-light">
                    <tr>
                        <th>ID Anggota</th>
                        <th>Nama</th>
                        <th>Transaksi Terakhir</th>
                        <th>Jumlah</th>
                        <th>Nominal</th>
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

<div class="modal fade" id="modalDetail" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded">
            <div class="block block-transparent mb-0">
                <form id="form-cabang" onsubmit="return false">
                    @csrf
                    <input type="hidden" id="field-id" value="" name="id"/>
                    <input type="hidden" value="add" id="method"/>
                    <div class="block-header bg-alt-secondary">
                        <h3 class="block-title" id="modal_title">Modal Title</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="form-group row mb-1">
                            <label class="col-4 mb-0">ID Anggota</label>
                            <div class="col-8">
                                <div class="form-control-plaintext text-left py-0" id="field-anggota_id"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-4 mb-0">Nama Lengkap</label>
                            <div class="col-8">
                                <div class="form-control-plaintext text-left py-0" id="field-nama"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-4 mb-0">Total Tunggakan</label>
                            <div class="col-8">
                                <div class="form-control-plaintext text-left py-0" id="field-jumlah"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-4 mb-0">Jumlah Tunggakan</label>
                            <div class="col-8">
                                <div class="form-control-plaintext text-left py-0" id="field-nominal"></div>
                            </div>
                        </div>
                        <hr class="border-2x">
                        <div id="list-tunggakan">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="{{ Module::asset('Simpanan:Assets/simkop/tunggakan.js') }}"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
@endpush
