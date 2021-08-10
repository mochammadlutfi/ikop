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
                        <th colspan="2" class="text-center">Nama Akun</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($klasifikasi as $k)
                    <tr>
                        <td width="3%">
                            <i class="fa fa-folder"></i>
                        </td>
                        <td colspan="3">
                            <b>{{ $k->kode }} {{ $k->nama }}</b>
                        </td>
                    </tr>
                    @if($k->akun)
                        @foreach ($k->akun as $akun)
                        <tr>
                            <td></td>
                            <td>{{ $akun->kode }} {{ $akun->nama }}</td>
                            <td>
                                {{ currency($akun->transaksi->where('jenis', 'pemasukan')->sum('jumlah')) }}</span>
                            </td>
                            <td>
                                0
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    @if ($k->sub)
                        @foreach ($k->sub as $sub)
                        <tr>
                            <td></td>
                            <td colspan="3"><b>{{ $sub->kode }} {{ $sub->nama }}</b></td>
                        </tr>
                        @if($sub->akun)
                            @foreach ($sub->akun as $akun)
                            <tr>
                                <td></td>
                                <td>{{ $akun->kode }} {{ $akun->nama }}</td>
                                <td>
                                    @if($sub->nama == 'Aktiva Lancar')
                                        @foreach($kas as $k)
                                            @if($k->nama == $akun->nama)
                                                {{ $k->trans_kas->where('jenis', 'pemasukan')->sum('jumlah') }}</span>
                                            @endif
                                        @endforeach
                                    @else
                                        {{ $akun->transaksi->where('jenis', 'pemasukan')->sum('jumlah') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sub->nama == 'Aktiva Lancar')
                                        @foreach($kas as $k)
                                            @if($k->nama == $akun->nama)
                                                {{ $k->trans_kas->where('jenis', 'pengeluaran')->sum('jumlah') }}</span>
                                            @endif
                                        @endforeach
                                    @else
                                        {{ $akun->transaksi->where('jenis', 'pengeluaran')->sum('jumlah') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        @endforeach
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="">
                        <td colspan="2" class="text-center">
                            Jumlah Total
                        </td>
                        <td>
                            -
                        </td>
                        <td>
                            -
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
