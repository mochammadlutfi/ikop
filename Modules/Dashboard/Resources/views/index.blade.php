@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Dashboard
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-secondary" id="date_filter">
                <i class="fa fa-calendar"></i>
                <span></span>
            </button>
        </div>
    </div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-xl-4">
            <div class="block block-rounded block-bordered block-link-shadow" id="dashboard-pembiayaan">
                <div class="block-header">
                    <h3 class="block-title">
                        Pembiayaan
                    </h3>
                </div>
                <div class="block-content block-content-full clearfix pt-0">
                    <div class="text-center">
                        <div class="spinner-border text-primary wh-50" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="block block-rounded block-bordered block-link-shadow" id="dashboard-simpanan">
                <div class="block-header">
                    <h3 class="block-title">
                        Transaksi Simpanan
                    </h3>
                </div>
                <div class="block-content block-content-full clearfix pt-0">
                    <div class="text-center">
                        <div class="spinner-border text-primary wh-50" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="block block-rounded block-bordered block-link-shadow" id="dashboard-kas">
                <div class="block-header">
                    <h3 class="block-title">
                        Transaksi Kas
                    </h3>
                </div>
                <div class="block-content block-content-full clearfix pt-0">
                    <div class="text-center">
                        <div class="spinner-border text-primary wh-50" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #2 -->
        <div class="col-md-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header">
                    <h3 class="block-title">
                        Transaksi Kas <small>7 Hari Lalu</small>
                    </h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="pull-all pt-15">
                        <!-- Lines Chart Container -->
                        {{-- {!! $chart_masuk->container() !!} --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- END Row #2 -->
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('js/plugins/chartjs/Chart.bundle.min.js') }}"></script>
<script src="{{ Module::asset('Dashboard:Assets/dashboard.js') }}"></script>
{{-- {!! $chart_masuk->script() !!} --}}
@endpush
