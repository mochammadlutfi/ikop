@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded block-transparent bg-gd-lake">
        <div class="block-content bg-pattern bg-black-op-25">
            <div class="py-20 text-center">
                <h1 class="font-w700 text-white mb-10">Keanggotaan</h1>
                <h2 class="h4 font-w400 text-white-op">Tambah Anggota Baru</h2>
            </div>
        </div>
    </div>
    
    <div class="block mb-lg-1">
        <div class="block-content p-0">
            <!-- Step Tabs -->
            <ul class="nav nav-tabs nav-tabs-block nav-fill">
                <li class="nav-item">
                    <a class="nav-link active" href="#">1. Data Pribadi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">2. Data Alamat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">3. Pembayaran</a>
                </li>
            </ul>
            <!-- END Step Tabs -->
        </div>
    </div>

    <div class="block block-shadow block-bordered block-rounded">
        <!-- Form -->
        <form id="form-step1" onsubmit="return false;">
            <input type="text" name="anggota_id" id="field-anggota_id" value="{{ empty($anggota->anggota_id) ? '' :  $anggota->anggota_id}}">
            <input type="hidden" name="method" id="method" value="create">
            <!-- Steps Content -->
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="text-center">
                            <img id="img_preview" src="{{ asset('media/placeholder/avatar.jpg') }}" class="img-fluid w-75">

                            <div class="btn btn-secondary w-75 mt-3">
                                <input type="hidden" id="featured_img" name="featured_img" value="">
                                <input type="file" class="file-upload" id="file-upload" accept="image/*">
                                <i class="fa fa-folder-open mr-1"></i>
                                Pilih Foto
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nomor KTP</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-no_ktp" class="form-control" name="no_ktp" value="{{ empty($anggota->no_ktp) ? '' :  $anggota->no_ktp}}">
                                <div class="invalid-feedback" id="error-no_ktp">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Lengkap</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-nama" class="form-control" name="nama" value="{{ empty($anggota->nama) ? '' :  $anggota->nama}}">
                                <div class="invalid-feedback" id="error-nama">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Jenis Kelamin</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="jk" id="jk1" value="L" value="{{ !empty($anggota->jk) &&  $anggota->jk == 'L' ? '' : 'checked="checked"' }}" checked="checked">
                                    <label class="custom-control-label" for="jk1">Laki-Laki</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="jk" id="jk2" value="P" value="{{ !empty($anggota->jk) &&  $anggota->jk == 'P' ? '' : 'checked="checked"' }}" >
                                    <label class="custom-control-label" for="jk2">Perempuan</label>
                                </div>
                                <div class="text-danger" id="error-jk"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Tempat Tanggal Lahir</label>
                            <div class="col-lg-4">
                                <input type="text" id="field-tmp_lahir" class="form-control" name="tmp_lahir" value="{{ empty($anggota->tmp_lahir) ? '' :  $anggota->tmp_lahir}}">
                                <div class="invalid-feedback" id="error-tmp_lahir">Invalid feedback</div>
                            </div>
                            <div class="col-lg-4">
                                <input type="text" id="field-tgl_lahir" class="form-control" name="tgl_lahir" value="{{ empty($anggota->tgl_lahir) ? '' :  $anggota->tgl_lahir}}">
                                <div class="invalid-feedback" id="error-tgl_lahir">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Status Perkawinan</label>
                            <div class="col-lg-8">
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status_pernikahan" id="status_pernikahan1" value="Lajang" value="{{ !empty($anggota->status_pernikahan) &&  $anggota->status_pernikahan == 'Lajang' ? '' : 'checked="checked"' }}" checked="checked">
                                    <label class="custom-control-label" for="status_pernikahan1">Lajang</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status_pernikahan" id="status_pernikahan2" value="Menikah" value="{{ !empty($anggota->status_pernikahan) &&  $anggota->status_pernikahan == 'Menikah' ? '' : 'checked="checked"' }}">
                                    <label class="custom-control-label" for="status_pernikahan2">Menikah</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status_pernikahan" id="status_pernikahan3" value="Duda/Janda" value="{{ !empty($anggota->status_pernikahan) &&  $anggota->status_pernikahan == 'Duda/Janda' ? '' : 'checked="checked"' }}">
                                    <label class="custom-control-label" for="status_pernikahan3">Duda/Janda</label>
                                </div>
                                <div class="text-danger" id="error-status_pernikahan"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >No. Handphone</label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            +62
                                        </span>
                                    </div>
                                    <input type="text" id="field-no_hp" class="form-control phone" name="no_hp" value="{{ empty($anggota->no_hp) ? '' :  $anggota->no_hp}}">
                                    <div class="invalid-feedback" id="error-no_hp">Invalid feedback</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >No. Telepon <sub class="text-danger">*Optional</sub></label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            +62
                                        </span>
                                    </div>
                                    <input type="text" id="field-telp" class="form-control phone" name="no_telp" value="{{ empty($anggota->no_telp) ? '' :  $anggota->no_telp}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Alamat Email<sub class="text-danger">*Optional</sub></label>
                            <div class="col-lg-8">
                                <input type="email" id="email" class="form-control" name="email" value="{{ empty($anggota->email) ? '' :  $anggota->email}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pendidikan Terakhir</label>
                            <div class="col-lg-8">
                                <select class="form-control" id="field-pendidikan" name="pendidikan" style="width: 100%;" data-placeholder="Pilih..">
                                    <option value="">Pilih</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'SD' ? $anggota->pendidikan : ''}} value="SD">SD</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'SMP' ? 'selected="selected"' : ''}} value="SMP">SMP</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'SMA/Sederajat' ? 'selected="selected"' : ''}} value="SMA/Sederajat">SMA/Sederajat</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'Akademi/Diploma' ? 'selected="selected"' : ''}} value="Akademi/Diploma">Akademi/Diploma</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'S1' ? 'selected="selected"' : ''}} value="S1">S1</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'S2' ? 'selected="selected"' : ''}} value="S2">S2</option>
                                    <option {{ !empty($anggota->pendidikan) && $anggota->pendidikan === 'S3' ? 'selected="selected"' : ''}} value="S3">S3</option>
                                </select>
                                <div class="invalid-feedback" id="error-pendidikan">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pekerjaan</label>
                            <div class="col-lg-8">
                                <select class="form-control" id="field-pekerjaan" name="pekerjaan" style="width: 100%;" data-placeholder="Pilih..">
                                    <option value="">Pilih</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Pelajar/Mahasiswa' ? 'selected="selected"' : ''}} value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Pegawai Swasta' ? 'selected="selected"' : ''}} value="Pegawai Swasta">Pegawai Swasta</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Pensiunan' ? 'selected="selected"' : ''}} value="Pensiunan">Pensiunan</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Ibu Rumah Tangga' ? 'selected="selected"' : ''}} value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Pegawai Negeri' ? 'selected="selected"' : ''}} value="Pegawai Negeri">Pegawai Negeri</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Guru' ? 'selected="selected"' : ''}} value="Guru">Guru</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Wiraswasta' ? 'selected="selected"' : ''}} value="Wiraswasta">Wiraswasta</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'TNI/Polisi' ? 'selected="selected"' : ''}} value="TNI/Polisi">TNI/Polisi</option>
                                    <option {{ !empty($anggota->pekerjaan) && $anggota->pekerjaan === 'Lainnya' ? 'selected="selected"' : ''}} value="Lainnya">Lainnya</option>
                                </select>
                                <div class="invalid-feedback" id="error-pekerjaan">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >Nama Ibu Kandung</label>
                            <div class="col-lg-8">
                                <input type="text" id="field-nama_ibu" class="form-control" name="nama_ibu"  value="{{ empty($anggota->nama_ibu) ? '' :  $anggota->nama_ibu}}">
                                <div class="invalid-feedback" id="error-nama_ibu">Invalid feedback</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4" for="field-ktp">Scan KTP</label>
                            <div class="col-lg-8">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="field-ktp" name="ktp" data-toggle="custom-file-input" accept=".pdf,image/*">
                                    <label class="custom-file-label label-ktp" for="field-ktp">Pilih file</label>
                                    <div id="error-ktp" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
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
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ Module::asset('Anggota:Assets/js/form.js') }}"></script>
@endpush