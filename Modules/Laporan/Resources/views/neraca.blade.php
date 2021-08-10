@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')

<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <a class="breadcrumb-item" href="{{ route('simla.riwayat') }}">Laporan</a>
            <span class="breadcrumb-item active">Neraca Saldo</span>
        </nav>
    </div>
</div>
<div class="content">
    <div class="content-heading pt-0 mb-3">
        Neraca Saldo
        <div class="float-right">
            <button type="button" class="btn btn-sm btn-secondary" id="tgl_range">
                <i class="fa fa-calendar"></i>
                <span></span>
                <input type="hidden" id="tgl_mulai" value="">
                <input type="hidden" id="tgl_akhir" value="">
            </button>
        </div>
    </div>
    <div class="block block-rounded ">
        <div class="block-content pb-15">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h1 class="font-w700 mb-10">Neraca Saldo</h1>
                    <h2 class="h4 font-w400">Periode</h2>
                </div>
            </div>
            <table class="table table-hover table-striped data-table font-size-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Akun</th>
                        <th>2020</th>
                        <th>2021</th>
                    </tr>
                </thead>
            </table>
            {{-- Kas & Bank --}}
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <td colspan="3">
                            <i class="fa fa-folder"></i>
                            <b>Aset Lancar</b>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="padding-left: 40px;font-weight: 600;">
                            Kas & Bank
                        </td>
                    </tr>
                    @foreach($kas as $k)
                    <tr>
                        <td style="padding-left: 55px;">
                            {{ $k->nama }}
                        </td>
                        <td>
                            {{ currency($k->transaksi->where('jenis', 'pemasukan')->sum('jumlah')) }}
                        </td>
                        <td>
                            {{ currency($k->transaksi->where('jenis', 'pengeluaran')->sum('jumlah')) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left: 55px;font-weight: 700;">
                            Jumlah Kas & Bank
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="3" style="padding-left: 40px;font-weight: 600;">
                            Piutang Usaha
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;">
                            Piutang Usaha
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;">
                            Piutang Anggota
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;font-weight: 700;">
                            Jumlah Piutang Usaha
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="padding-left: 15px;font-weight: 700;">
                            JUMLAH ASET LANCAR
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <td colspan="3">
                            <i class="fa fa-folder"></i>
                            <b>Aset Tidak Lancar</b>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="padding-left: 40px;font-weight: 600;">
                            Kas & Bank
                        </td>
                    </tr>
                    @foreach($kas as $k)
                    <tr>
                        <td style="padding-left: 55px;">
                            {{ $k->nama }}
                        </td>
                        <td>
                            {{ currency($k->transaksi->where('jenis', 'pemasukan')->sum('jumlah')) }}
                        </td>
                        <td>
                            {{ currency($k->transaksi->where('jenis', 'pengeluaran')->sum('jumlah')) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left: 55px;font-weight: 700;">
                            Jumlah Kas & Bank
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="3" style="padding-left: 40px;font-weight: 600;">
                            Piutang Usaha
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;">
                            Piutang Usaha
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;">
                            Piutang Anggota
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;font-weight: 700;">
                            Jumlah Piutang Usaha
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="padding-left: 15px;font-weight: 700;">
                            JUMLAH ASET LANCAR
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                        <td>
                            {{ currency(0) }}
                        </td>
                    </tr>
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
<script src="{{ Module::asset('Laporan:Assets/neraca.js') }}"></script>
@endpush
