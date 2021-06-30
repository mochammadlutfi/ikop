@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection


@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-4">
            <div class="block">
                <form id="form-klasifikasi" onsubmit="return false;">
                    @csrf
                    <input type="hidden" id="method" value="create">
                    <input type="hidden" name="id" value="">
                    <div class="block-header block-header-default">
                        <h3 class="block-title" id="form-title">Tambah Klasifikasi Akun</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="form-group">
                            <label class="col-form-label" for="field-kode">Kode</label>
                            <input type="text" class="form-control" id="field-kode" name="kode">
                            <div class="invalid-feedback" id="error-kode">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="field-nama">Nama</label>
                            <input type="text" class="form-control" id="field-nama" name="nama">
                            <div class="invalid-feedback" id="error-nama">Invalid feedback</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="field-induk_id">Induk</label>
                            <select class="form-control" name="induk_id" id="field-induk_id"></select>
                            <div class="invalid-feedback" id="error-induk_id">Invalid feedback</div>
                        </div>
                        
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
        <div class="col-lg-8">
            <!-- Default Elements -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Kelola Data Kategori</h3>
                </div>
                <div class="block-content pb-15">
                    <div class="row">
                        <div class="col-lg-12">
                            <div  id="list" class="drag_disabled dd">
                                {{-- <ol class="dd-list">
                                    @foreach($induk as $k)
                                    <li class="dd-item" data-id="11">
                                        <div class="dd-handle">
                                            {{ $k->kode }}  {{ $k->nama }}</div>
                                        @if ($k->sub)
                                        <ol class="dd-list">
                                            @foreach ($k->sub as $sub)
                                            <li class="dd-item" data-id="12">
                                                <div class="dd-handle">
                                                    {{ $sub->kode }}  {{ $sub->nama }}
                                                </div>
                                            </li>
                                            @endforeach
                                        </ol>
                                        @endif
                                    </li>
                                    @endforeach
                                </ol> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Default Elements -->
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Keuangan:Assets/js/akun_klasifikasi.js') }}"></script>
@endpush
