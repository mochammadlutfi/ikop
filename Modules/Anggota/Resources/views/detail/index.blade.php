@extends('layouts.master')
@section('styles')
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
<!-- Main Content -->
<div class="content">
    <div class="block block-rounded block-transparent bg-gd-lake">
        <div class="block-content bg-pattern bg-black-op-25">
            <div class="py-20 text-center">
                <h1 class="font-w700 text-white mb-10">Keanggotaan</h1>
                <h2 class="h4 font-w400 text-white-op">Detail Anggota</h2>
            </div>
        </div>
    </div>
    <div class="block">
        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('anggota.detail.biodata', $anggota->anggota_id) }}" data-target="#biodata" data-toggle="tabajax">Biodata</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('anggota.detail.simpanan', $anggota->anggota_id) }}" data-target="#simpanan" data-toggle="tabajax">Simpanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('anggota.detail.simpanan', $anggota->anggota_id) }}" data-target="#pembiayaan" data-toggle="tabajax">Pembiayaan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('anggota.detail.transaksi', $anggota->anggota_id) }}" data-target="#transaksi" data-toggle="tabajax">Riwayat Transaksi</a>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane active" id="biodata" role="tabpanel">
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

                        <a href="" class="btn btn-secondary btn-block">
                            <i class="si si-note mr1"></i> Ubah
                        </a>
                        <a href="" class="btn btn-outline-danger btn-block">
                            <i class="si si-trash mr1"></i> Hapus
                        </a>
                    </div>
                    <div class="col-lg-9">
                        
                        <div class="mb-3">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <div class="font-size-18 font-weight-bold">
                                        Informasi Dasar
                                    </div>
                                    <hr class="border-bottom my-1"/>
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
            <div class="tab-pane" id="simpanan" role="tabpanel">
                <div class="row gutters-tiny">
                    <!-- Row #1 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="block block-shadow-2 block-bordered">
                            <div class="block-content block-content-full text-right">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Simpanan Pokok</div>
                                <div class="font-size-h2 font-w700"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="block block-shadow-2 block-bordered">
                            <div class="block-content block-content-full text-right">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Simpanan Wajib</div>
                                <div class="font-size-h2 font-w700"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="block block-shadow-2 block-bordered">
                            <div class="block-content block-content-full text-right">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Simpanan Sukarela</div>
                                <div class="font-size-h2 font-w700"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="block block-shadow-2 block-bordered">
                            <div class="block-content block-content-full text-right">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Simpanan Sosial</div>
                                <div class="font-size-h2 font-w700"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END Row #1 -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
    <script src="{{ Module::asset('Anggota:Assets/js/detail.js') }}"></script>
@endpush
