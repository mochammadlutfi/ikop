@extends('layouts.master')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Kelola Slider
        <a href="{{ route('slider.tambah') }}" class="btn btn-secondary mr-5 mb-5 float-right btn-rounded">
            <i class="si si-plus mr-5"></i>
            Tambah Slider Baru
        </a>
    </div>
    <div class="block block-rounded block-shadow block-bordered d-md-block d-none mb-10">
        <div class="block-content p-2">
            <div class="row justify-content-between">
                <div class="col-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="pencarian" name="keyword"
                            placeholder="Masukan Kata Kunci">
                    </div>
                </div>
                <div class="col-4">
                    <select class="form-control" id="kategori" placeholder="Semua Kategori"></select>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-md-8 py-lg-2" id="content-nav">
                            <span>Navigasi</span>
                        </div>
                        <div class="col-md-4 pt-25 pl-0">
                            <button type="button" class="btn btn-alt-secondary float-right" id="nextProduk">
                                <i class="fa fa-chevron-right fa-fw"></i>
                            </button>
                            <button type="button" class="btn btn-alt-secondary float-left" id="prevProduk">
                                <i class="fa fa-chevron-left fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="current_page" value="1">
    <div class="row" id="data-list"></div>
    <div class="row justify-content-center my-15" id="loading">
        <div class="col-lg-6 text-center">
            <div class="spinner-border text-primary wh-50" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
<script src="{{ Module::asset('Slider:Assets/slider.js') }}"></script>
<script>
</script>
@endpush
