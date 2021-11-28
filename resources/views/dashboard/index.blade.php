@extends('layouts.master')

@section('content')
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Dashboard
    </div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-xl-4">
            @widget('Dashboard\PembiayaanWidget')
        </div>
        <div class="col-6 col-xl-4">
            <div class="block block-rounded block-bordered block-link-shadow" id="simpanan">
                <div class="block-header">
                    <h3 class="block-title">
                        Transaksi Simpanan
                    </h3>
                </div>
                <div class="block-content block-content-full clearfix">
                    <div class="row">
                        <div class="col-lg-5">
                            Rp <span class="display_currency font-size-md font-w600">Rp.1213</span>
                        </div>
                        <div class="col-lg-6">
                            Simpanan Anggota
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            Rp <span class="display_currency font-size-md font-w600">Rp. 15123</span>
                        </div>
                        <div class="col-lg-6">
                            Penarikan Tunai
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            Rp <span class="display_currency font-size-md font-w600">Rp. 15123</span>
                        </div>
                        <div class="col-lg-6">
                            Jumlah Simpanan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-header">
                    <h3 class="block-title">
                        Transaksi Kas
                    </h3>
                </div>
                <div class="block-content block-content-full clearfix">
                    <div class="row">
                        <div class="col-lg-5">
                            Rp <span class="display_currency font-size-md font-w600">Debit</span>
                        </div>
                        <div class="col-lg-6">
                            Debit
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            Rp <span class="display_currency font-size-md font-w600">Kredit</span>
                        </div>
                        <div class="col-lg-6">
                            Kredit
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            Rp <span class="display_currency font-size-md font-w600">Jumlah</span>
                        </div>
                        <div class="col-lg-6">
                            Jumlah
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="row invisible" data-toggle="appear">
        <!-- Row #2 -->
        <div class="col-md-12">
            <div class="block block-rounded block-bordered">
                <div class="block-header">
                    <h3 class="block-title">
                        Riwayat Transaksi <small>7 Hari Lalu</small>
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
<script src="{{ asset('js/plugins/chartjs/Chart.bundle.min.js') }}"></script>
<script src="{{ Module::asset('Dashboard:Assets/dashboard.js') }}"></script>
{{-- {!! $chart_masuk->script() !!} --}}
@endpush
