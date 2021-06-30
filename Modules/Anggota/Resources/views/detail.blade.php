@extends('layouts.master')
@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<style type="text/css">
    .nounderline, .violet{
        color: #7c4dff !important;
    }
    .btn-dark {
        background-color: #7c4dff !important;
        border-color: #7c4dff !important;
        width: 100%
    }
    .btn-dark .file-upload {
        width: 100%;
        padding: 10px 0px;
        position: absolute;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .profile-img img{
        width: 200px;
        height: 200px;
        border-radius: 50%;
    }
</style>
@endsection
@section('content')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('anggota') }}">Anggota</a>
            <span class="breadcrumb-item active">{{ $anggota->nama }}</span>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Anggota
    </div>
    <div class="block mb-2">
        <div class="block-content p-0">
            <ul class="nav nav-tabs nav-tabs-alt nav-fill nav-tabs-block" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('anggota.detail.biodata', $anggota->anggota_id) }}" data-target="#biodata" data-toggle="tabajax">Biodata</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('anggota.detail.simpanan', $anggota->anggota_id) }}" data-target="#simpanan" data-toggle="tabajax">Simpanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('anggota.detail.transaksi', $anggota->anggota_id) }}" data-target="#transaksi" data-toggle="tabajax">Transaksi</a>
                </li>
            </ul>
        </div>
    </div>
    <input type="hidden" id="anggota_id" value="{{ $anggota->anggota_id }}">
    <div class="tab-content">
        <div class="tab-pane active" id="biodata" role="tabpanel">
            <div class="block">
                <div class="block-content">
                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="text-center">
                                <img id="foto" src="{{ asset('media/placeholder/avatar.jpg') }}" class="img-fluid w-75">
                            </div>
                            
                            <div class="form-group row my-3 text-center">
                                <label class="col-6 col-lg-12 mb-0 font-size-18 font-size-14-down-lg ">Status</label>
                                <div class="col-6 col-lg-12">
                                    <div class="form-control-plaintext py-0 font-size-18 font-size-14-down-lg">
                                        {!! $anggota->status_badge !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row my-3 text-center">
                                <label class="col-6 col-lg-12 mb-0 font-size-18 font-size-14-down-lg ">Tanggal Bergabung</label>
                                <div class="col-6 col-lg-12">
                                    <div class="form-control-plaintext py-0 font-size-18 font-size-14-down-lg">
                                        {{ Carbon\Carbon::parse($anggota->created_at)->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="mb-3">
                                <div class="mb-3 row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <div class="font-size-18 font-weight-bold">
                                                Informasi Dasar
                                            </div>
                                            <button class="btn btn-sm btn-secondary" id="ubahProfile" data-id="{{ $anggota->anggota_id }}">
                                                <i class="si si-note mr-1"></i> Ubah 
                                            </button>
                                        </div>
                                        <hr class="border-bottom my-1"/>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">ID Anggota</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->anggota_id }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">NIK</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->no_ktp }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Nama Lengkap</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->nama }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Jenis Kelamin</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Tempat/Tanggal Lahir</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->tmp_lahir }} / {{ $anggota->tgl_lahir }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Status Perkawinan</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->status_pernikahan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Nomor HP</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->no_hp }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Alamat Email</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Pendidikan Terakhir</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->pendidikan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Pekerjaan</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->pekerjaan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">Ibu Kandung</label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $anggota->nama_ibu }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="mb-3 row">
                                    <div class="col-12">
                                        <div class="font-size-18 font-weight-bold">
                                            Informasi Alamat
                                        </div>
                                        <hr class="border-bottom my-1"/>
                                    </div>
                                </div>
                                @foreach($alamat as $a)
                                <div class="form-group row mb-1">
                                    <label class="col-6 col-lg-3 mb-0 font-size-18 font-size-14-down-lg ">
                                        Alamat <sub class="text-danger">{{ $a->domisili == 1 ? '*Domisili' : '*Identitas' }}</sub>
                                    </label>
                                    <div class="col-6 col-lg-9">
                                        <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                            : {{ $a->alamat ." RT ". $a->rt ." RW ". $a->rw }} <br>
                                            {{ $a->daerah }}, {{ $a->pos }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="simpanan" role="tabpanel">
            <div class="row gutters-tiny" id="simpanan-list"></div>

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
                            <select class="form-control" name="akun">
                                <option value="">Pilih Simpanan</option>
                                <option value="">Simpanan Wajib</option>
                                <option value="">Simpanan Sukarela</option>
                            </select>
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
            
            <div class="block block-rounded block-shadow-2 block-bordered mb-5">
                <div class="block-content px-0 py-0">
                    <input type="hidden" id="current_page" value="1">
                    <table class="table table-striped table-vcenter table-hover mb-0 font-size-sm" id="data-list">
                        <thead class="thead-light">
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="15%">No. Transaksi</th>
                                <th>Keterangan</th>
                                <th width="15%">Jumlah</th>
                                <th width="15%">Penerima</th>
                                <th class="text-center" width="10%">Aksi</th>
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

        <div class="tab-pane" id="transaksi" role="tabpanel">
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
            
            <div class="block block-rounded block-shadow-2 block-bordered mb-5">
                <div class="block-content px-0 py-0">
                    <input type="hidden" id="current_page" value="1">
                    <table class="table table-striped table-vcenter table-hover mb-0 font-size-sm" id="data-list">
                        <thead class="thead-light">
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="15%">No. Transaksi</th>
                                <th>Keterangan</th>
                                <th width="15%">Jumlah</th>
                                <th width="15%">Penerima</th>
                                <th class="text-center" width="10%">Aksi</th>
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

    </div>
</div>

@include('anggota::modal.updateProfil')

@stop
@push('scripts')
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ Module::asset('Anggota:Assets/js/detail.js') }}"></script>
@endpush
