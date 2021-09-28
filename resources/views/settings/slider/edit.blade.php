@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
@endsection

@section('content')
<div class="content">
    <form id="form-slide" onsubmit="return false;" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $data->id }}">
        <input type="hidden" id="metode" value="update">
        <div class="content-heading pt-3 mb-3">
            Ubah Slide
            <button type="submit" class="btn btn-primary float-right">
                <i class="si si-check mr-3"></i> Simpan
            </button>
        </div>
        <div class="block block-shadow block-rounded block-bordered">
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label" for="field-nama">Jenis</label>
                            <select class="form-control" name="type" id="field-type">
                                <option value="">Pilih</option>
                                <option value="1" {{ ($data->type == '1') ? 'selected="selected"' : '' }}>Berita</option>
                                <option value="2" {{ ($data->type == '2') ? 'selected="selected"' : '' }}>Halaman</option>
                                <option value="3" {{ ($data->type == '3') ? 'selected="selected"' : '' }}>Custom Link</option>
                            </select>
                            <div id="error-type" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group {{ ($data->type == '1' || $data->type == '2') ? '' : 'd-none' }}" id="slug">
                            <label class="col-form-label" for="field-slug">Berita/Halaman</label>
                            <select class="form-control" name="slug" id="field-slug" data-id="{{ $data->url }}" data-text="{{ $data->slug }}"></select>
                            <div id="error-slug" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group {{ ($data->type == '3') ? '' : 'd-none' }}" id="link">
                            <label class="col-form-label" for="field-link">Link</label>
                            <input type="text" class="form-control" name="link" id="field-link" placeholder="Masukan Link / URL" value="{{ $data->url }}">
                            <div id="error-link" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="css-control css-control-primary css-radio">
                                    <input type="radio" class="css-control-input" id="status_publikasi" name="status" value="1" checked>
                                    <span class="css-control-indicator"></span> Publikasikan
                                </label>
                                <label class="css-control css-control-danger css-radio">
                                    <input type="radio" class="css-control-input" id="status_draft" name="status" value="0">
                                    <span class="css-control-indicator"></span> Draft
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row gutters-tiny items-push">
                            <label class="col-12">Thumbnail</label>
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <img id="img_preview" src="{{ asset(empty($data->img) ? "img/slider.png" : $data->img) }}" width="100%">
                                </div>
                                <div class="text-danger" id="error-foto"></div>
                                <div class="btn btn-primary btn-block mt-15">
                                    <input type="hidden" id="featured_img" name="featured_img" value="">
                                    <input type="file" class="file-upload" id="file-upload" accept="image/*">
                                    Pilih Foto
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('include.modal_crop')
@stop

@push('scripts')
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
<script src="{{ Module::asset('Slider:Assets/slider.js') }}"></script>
<script>
jQuery(document).ready(function () {
    if($('#field-type').val() == '1' || $('#field-type').val() == '2')
    {
        if($('#field-type').val() === '1')
        {
            link = laroute.route('slider.post_json');
            placeholder = 'Pilih Berita';
        }else if($('#field-type').val() === '2')
        {     
            link = laroute.route('slider.pages_json');
            placeholder = 'Pilih Halaman';
        }
        slugSelect = $("#field-slug").select2({
            placeholder: placeholder,
            theme : 'bootstrap4',
            ajax: {
                url: link,
                type: 'POST',
                dataType: 'JSON',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        
        slugOption = new Option($('#field-slug').attr("data-text"), $('#field-slug').attr("data-id"), true, true);
        slugSelect.append(slugOption).trigger('change');
    }
});
</script>
@endpush
