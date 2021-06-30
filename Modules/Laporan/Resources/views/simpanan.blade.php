@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Laporan Simpanan
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-secondary" id="tgl_range">
                <i class="fa fa-calendar"></i>
                <span></span>
                <input type="hidden" id="tgl_mulai" value="">
                <input type="hidden" id="tgl_akhir" value="">
            </button>
        </div>
        
        {{-- <div class="float-right">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="si si-calendar"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="" name="">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="si si-calendar"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="" name="">
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="block block-rounded block-shadow-2 block-bordered mb-5">
        <div class="block-content block-content-full">
            
            <div class="row text-center">
                <div class="col-lg-12">
                    <h1 class="font-w700 mb-10">LAPORAN SIMPANAN</h1>
                    <h2 class="h4 font-w400 periode">Periode</h2>
                </div>
            </div>
            <table class="table table-striped table-vcenter table-hover mb-0 font-size-sm" id="data-list">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Simpanan</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ Module::asset('Laporan:Assets/simpanan.js') }}"></script>
@endpush
