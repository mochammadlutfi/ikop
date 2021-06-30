<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SLIP SETORAN - {{ $invoice->no_transaksi }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/print.css') }}">

    <style type="text/css" media="all">
        @page {
            size: 210mm 165mm;
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
                    <h6 class="text-left font-w700 mb-0">SIMPANAN KOPERASI</h6>
                </td>
                <td align="right" style="width: 40%;">
                    <img src="{{ asset('assets/img/logo/logo_slip.png') }}" height="40px">
                    <img src="{{ asset('assets/img/logo/logo_koperasi.png') }}" height="40px">
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
                    <span class="ml-3">: {{ GeneralHelp::tgl_indo($invoice->created_at) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color:#007a37;padding:3px;border:1px solid #007a37">
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
                                        <td>: {{ $alamat->alamat." RT ". $alamat->rt ." RW ". $alamat->rw }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>:
                                            {{ "Kel. ". ucwords(strtolower($alamat->kelurahan->nama)). ", Kec. ". ucwords(strtolower($alamat->kecamatan->nama))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>: {{ ucwords(strtolower($alamat->kota->name))}}</td>
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
                                    <tr>
                                        <td width="30%"><b>No. Transaksi</b></td>
                                        <td>: {{ $invoice->no_transaksi }}</td>
                                    </tr>
                                </table>
                                <table style="width:99%" class="produk" align="center">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 60px;"></th>
                                            <th class="text-center">Jenis Simpanan</th>
                                            <th class="text-center">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no=1 ;?>
                                        @foreach(json_decode($invoice->item) as $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-left">Simpanan {{ $item->produk }}</td>
                                            <td class="text-left">Rp {{ number_format($item->jumlah,0,",",".") }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2" class="font-w700 text-uppercase text-left">Total</td>
                                            <td class="font-w700 text-left">Rp
                                                {{ number_format($invoice->total,0,",",".") }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width:100%">
                                    <tr>
                                        <td width="30%"><b>Terbilang</b></td>
                                        <td>: {{ ucwords(TransaksiHelp::terbilang($invoice->total)) }} Rupiah </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="utama">
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
                            <td class="text-center" style="width: 200px;">{{ $invoice->teller->anggota->nama }}</td>
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
    </div>
</body>

</html>
