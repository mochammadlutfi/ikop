@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')

<div class="content">
    <div class="block block-rounded block-transparent bg-gd-lake">
        <div class="block-content bg-pattern bg-black-op-25">
            <div class="py-20 text-center">
                <h1 class="font-w700 text-white mb-10">Laporan</h1>
                <h2 class="h4 font-w400 text-white-op">Buku Besar</h2>
            </div>
        </div>
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Data Pegawai</h3>
            <button type="button" id="tambah" class="btn btn-alt-primary mr-5"><i class="si si-plus"></i> Tambah Pegawai</button>
        </div>
        <div class="block-content">

            @php
            $i = 1;
            $tot_debet = 0;
            $tot_kredit = 0;
            $t_saldo = 0;
            @endphp
            @foreach($kas as $k)
            <h2 class="content-heading pt-0 font-size-md">{{ $k->nama }}</h2>
            @if($k->trans_kas)
            <table class="table table-hover table-striped data-table font-size-sm">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis Transaksi</th>
                        <th>Keterangan</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($k->trans_kas as $t)
                    <tr>
                        <td>
                            {{ $i++ }}
                        </td>
                        <td>
                            {{ GeneralHelp::tgl_indo($t->created_at) }}
                        </td>
                        <td>
                            {{ $t->jenis }}
                        </td>
                        <td>
                            {{ $t->keterangan }}
                        </td>
                        <td>
                            @if($t->jenis == 'pemasukan')
                                @php
                                    $tot_debet += $t->jumlah;
                                @endphp
                                Rp <span class="display_currency">{{ $t->jumlah }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($t->jenis == 'pengeluaran' || $t->jenis == 'transfer')
                                @php
                                    $tot_kredit += $t->jumlah;
                                @endphp
                                Rp <span class="display_currency">{{ $t->jumlah }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @php
                                $saldo = $tot_debet - $tot_kredit;
                                $t_saldo = $saldo;
                            @endphp
                                Rp <span class="display_currency">{{ $saldo }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @endforeach
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ Module::asset('Laporan:Assets/buku_besar.js') }}"></script>
@endpush
