@extends('layouts.master')

@section('styles')

<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Registrasi Anggota Baru
    </div>
    
    <div class="block mb-lg-1">
        <div class="block-content p-0">
            <!-- Step Tabs -->
            <ul class="nav nav-tabs nav-tabs-alt nav-fill nav-tabs-block">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('anggota.tambah') }}">1. Data Pribadi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('anggota.tambah.step2') }}">2. Data Alamat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('anggota.tambah.step3') }}">3. Pembayaran</a>
                </li>
            </ul>
            <!-- END Step Tabs -->
        </div>
    </div>
    
    <div class="block block-shadow block-bordered block-rounded">
        <!-- Form -->
        <form id="form-step2" onsubmit="return false;">
            <input type="hidden" name="method" id="method" value="create">
            <!-- Steps Content -->
            <div class="block-content block-content-full">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        {{-- Start Alamat Identitas --}}
                        <div class="mb-3 row">
                            <div class="col-12">
                                <div class="font-size-18 font-weight-bold">
                                    Alamat Identitas
                                </div>
                                <span class="text-danger">*Diisi sesuai dengan data KTP</span>
                                <hr class="border-bottom my-1"/>
                            </div>
                        </div>
                        <input type="hidden" name="domisili" value="1">
                        <div class="form-group">
                            <label class="col-form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="field-alamat" name="alamat" rows="3">{{ !empty($alamat[0]['alamat']) ? $alamat[0]['alamat'] : ''}}</textarea>
                            <div class="invalid-feedback" id="error-alamat">Invalid feedback</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="field-wilayah">Wilayah</label>
                                    <select class="form-control wilayah" name="wilayah_id" id="field-wilayah_id"  data-id="{{ !empty($alamat[0]['wilayah_id']) ? $alamat[0]['wilayah_id'] : ''}}" data-text="{{ !empty($alamat[0]['wilayah_text']) ? $alamat[0]['wilayah_text'] : ''}}"></select>
                                    <div class="invalid-feedback" id="error-wilayah_id">Invalid feedback</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-kode_pos">Kode POS</label>
                                    <input type="text" id="field-kode_pos" name="kode_pos" class="form-control" value="{{ !empty($alamat[0]['pos']) ? $alamat[0]['pos'] : ''}}">
                                    <div class="invalid-feedback" id="error-kode_pos">Invalid feedback</div>
                                </div>
                            </div>
                        </div>
                        {{-- End Alamat Identitas --}}

                        <hr class="border-3x">
                        {{-- Start Alamat Identitas --}}
                        <div class="mb-3 row">
                            <div class="col-12">
                                <div class="font-size-18 font-weight-bold">
                                    Alamat Domisili
                                </div>
                                <span class="text-danger">*Diisi apabila alamat domisili tidak sesuai dengan identitas</span>
                                <hr class="border-bottom my-1"/>
                            </div>
                        </div>
                        <input type="hidden" name="domisili2" value="0">
                        <div class="form-group">
                            <label class="col-form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="field-alamat2" name="alamat2" rows="3">{{ !empty($alamat[1]['alamat']) ? $alamat[1]['alamat'] : ''}}</textarea>
                            <div class="invalid-feedback" id="error-alamat2">Invalid feedback</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="field-wilayah_id2">Wilayah</label>
                                    <select class="form-control" name="wilayah_id2" id="field-wilayah_id2" data-id="{{ !empty($alamat[1]['wilayah_id']) ? $alamat[1]['wilayah_id'] : ''}}" data-text="{{ !empty($alamat[1]['wilayah_text']) ? $alamat[1]['wilayah_text'] : ''}}"></select>
                                    <div class="invalid-feedback" id="error-wilayah_id2">Invalid feedback</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-kode_pos2">Kode POS</label>
                                    <input type="text" id="field-kode_pos2" name="kode_pos2" class="form-control" value="{{ !empty($alamat[1]['pos']) ? $alamat[1]['pos'] : ''}}">
                                    <div class="invalid-feedback" id="error-kode_pos2">Invalid feedback</div>
                                </div>
                            </div>
                        </div>
                        {{-- End Alamat Identitas --}}
                    </div>
                </div>
            </div>
            <!-- END Steps Content -->

            <!-- Steps Navigation -->
            <div class="block-content block-content-sm block-content-full bg-body-light">
                <div class="row">
                    <div class="col-6">
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-alt-secondary" data-wizard="next">
                            Selanjutnya 
                            <i class="fa fa-angle-right ml-5"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- END Steps Navigation -->
        </form>
        <!-- END Form -->
    </div>

</div>
@stop
@push('scripts')
    
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Anggota:Assets/js/form.js') }}"></script>
@endpush