<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SLIP SETORAN - {{ $invoice->no_transaksi }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">

    <style type="text/css" media="all">
        @page {
            size: 210mm 120mm;
            margin: 0px;
        }

        body {
            margin: 0px;
            background-color: white;
            font-size: 3px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: 11px;
            width: 100%;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: 11px;
        }

        .info-peminjam {
            margin-left: 15px;
            margin-right: 15px;
        }

        .information {
            background-color: #f6f7f9;
        }

        .information .logo {
            margin: 5px;
        }

        .produk {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        .utama {
            border: 1px solid #007a37;
            padding: 0;
            vertical-align: top;
        }

        .produk td {
            border: 1px solid #007a37;
        }

        .produk th {
            padding: 2px;
            border: 1px solid #007a37;
            background-color: #007a37;
            color: white;
        }

        .garis {
            border-bottom: 1px solid #007a37;
        }

        .info td {
            padding: 2px;
        }

    </style>
</head>

<body>
    <div class="information">
        <table width="100%" style="padding:10px 15px 10px 15px;">
            <tr>
                <td align="left" style="width: 60%;">
                    <h4 class="text-left font-w700 mb-0">SLIP SETORAN</h4>
                    <h6 class="text-left font-w700 mb-0">SIMPANAN SUKARELA</h6>
                </td>
                <td align="right" style="width: 40%;">
                    <img src="{{ asset('media/logo/logo_slip.png') }}" height="40px">
                    <img src="{{ asset('media/logo/logo_koperasi.png') }}" height="40px">
                </td>
            </tr>

        </table>
    </div>
    <div class="info-peminjam">
        <table class="tabel">
            <tr>
                <td colspan="2">
                    Bismillahirrahmanirrahim
                </td>
            </tr>
            <tr>
                <td>
                    <b>Cabang</b>
                    <span class="ml-3">: Kantor Utama</span>
                </td>
                <td>
                    <b>Tanggal</b>
                    <span class="ml-3">: {{ Date::parse($invoice->created_at)->format('l d F Y') }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color:#007a37;padding:3px;">
                    <span class="text-white">
                        <b>Harap ditulis dengan huruf cetak</b>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%" class="utama">
                    <table style="width:100%;" class="info">
                        <tr>
                            <td class="garis">
                                <b>Validasi</b>
                                <br>
                                <br>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Atas Transaksi Ini Dibebankan Ke Anggota</b>
                                <table style="width:100%">
                                    <tr>
                                        <td width="40%"><b>No. Anggota</b></td>
                                        <td>: {{ $invoice->anggota->anggota_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Nama Anggota</b></td>
                                        <td>: {{ $invoice->anggota->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Alamat</b></td>
                                        <td>: {{ $invoice->anggota->alamat_full }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Jenis Transaksi</b></td>
                                        <td>: {{ ucwords($invoice->jenis) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="utama">
                    <table style="width:100%;" class="info">
                        <tr>
                            <td>
                                <table style="width:100%">
                                    <tr class="garis">
                                        <td width="30%"><b>No. Transaksi</b></td>
                                        <td>: {{ $invoice->no_transaksi }}</td>
                                    </tr>
                                    <tr class="garis">
                                        <td width="30%"><b>Jumlah</b></td>
                                        <td>: Rp {{ number_format(abs($invoice->total),0,",",".") }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="garis">
                                <table style="width:100%">
                                    <tr>
                                        <td width="30%"><b>Terbilang</b></td>
                                        <td>: {{ ucwords(terbilang($invoice->total)) }} Rupiah </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width: 100%;">
                                    <tr>
                                        <td><br></td>
                                        <td><br></td>
                                    </tr>
                                    <tr>
                                        <td><br></td>
                                        <td> <br></td>
                                    </tr>
                                    <tr>
                                        <td> <br></td>
                                        <td><br> </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 200px;">----------------------</td>
                                        <td class="text-center" style="width: 200px;">----------------------</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 200px;">
                                            {{ $invoice->teller->anggota->nama }}</td>
                                        <td class="text-center" style="width: 200px;">{{ $invoice->anggota->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="width: 200px;">Teller</th>
                                        <th class="text-center" style="width: 200px;">Penyetor</th>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
