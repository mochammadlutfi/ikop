@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}"/>
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection


@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Detail Transaksi
        <div class="float-right">
            <button type="button" class="btn btn-secondary btn-sm">
                <i class="si si-printer"></i> Print
            </button>
            <a class="btn btn-secondary btn-sm js-tooltip" id="btn-edit" data-toggle="tooltip" data-placement="top" title="Ubah"  href="javascript:void(0)" data-id="{{ $data->kd_trans_kas }}">
                <i class="si si-note"></i> Ubah
            </a>
            <a class="btn btn-secondary btn-sm js-tooltip" id="btn-delete" data-toggle="tooltip" data-placement="top" title="Hapus" href="javascript:void(0);" data-id="{{ $data->kd_trans_kas }}">
                <i class="si si-trash"></i> Hapus
            </a>
        </div>
    </div>
    <div class="block block-shadow block-rounded-2 block-bordered">
        <div class="bg-body-light block-content">
            <div class="row no-gutters py-15 ">
                <div class="col-lg-6 d-flex">
                    <div class="my-auto">
                        <h2 class="font-w700 my-auto text-left">BUKTI TRANSFER KAS</h2>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="float-right">
                        <img src="{{ asset('media/logo/logo_slip.png') }}" class="img-fluid height-75">
                        <img src="{{ asset('media/logo/logo_koperasi.png') }}" class="img-fluid height-75">
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content pt-3 px-3" id="print_ini">
            <div class="row my-5">
                <div class="col-lg-12">
                    <span>Bismillahirrahmanirrahim</span>
                </div>
                <div class="col-lg-6">
                    <b>Cabang</b>
                    <span class="ml-3">: Kantor Utama</span>
                </div>
                <div class="col-lg-6">
                    <b>Tanggal</b>
                    <span class="ml-3">: {{ Carbon\Carbon::parse($data->tgl)->format('d F Y') }}</span>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-lg-12">
                    <div class="py-5 px-5 bg-primary">
                        <span class="text-white">
                            <b>Harap ditulis dengan huruf cetak</b>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>
            <!-- END Invoice Info -->

            <!-- Footer -->
            <p class="text-muted text-center">Transaksi ini sah apabila slip bukti setoran di validasi dan dibutuhi
                tanda
                tangan teller</p>
            <!-- END Footer -->
        </div>
    </div>
    <!-- END Invoice -->
</div>
@include('keuangan::kas_transfer.form')
@stop
@push('scripts')
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/i18n/id.js') }}"></script>
<script src="{{ Module::asset('Keuangan:Assets/js/kas_transfer.js') }}"></script>
@endpush
