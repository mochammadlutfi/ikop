<ul class="nav-main">
    <li>
        <a class="{{ Request::is('dashboard') ? 'active' : null }}" href="{{ route('dashboard') }}">
            <i class="si si-cup"></i>
            Dashboard
        </a>
    </li>
    <li>
        <a class="{{ Request::is('cabang') ? 'active' : null }}" href="{{ route('cabang') }}">
            <i class="fa fa-building"></i>Cabang
        </a>
    </li>
    <li class="{{ Request::is('anggota/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-users"></i><span class="sidebar-mini-hide">Anggota</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('anggota/tambah') ? 'active' : null }}" href="{{ route('anggota.tambah') }}">Tambah Anggota</a>
            </li>
            <li>
                <a class="{{ Request::is('anggota/list') ? 'active' : null }}" href="{{ route('anggota') }}">Data Anggota</a>
            </li>
        </ul>
    </li>

    
    <li>
        <a class="{{ Request::is('pembayaran/*', 'pembayaran') ? 'active' : null }}" href="{{ route('pembayaran') }}">
            <i class="fa fa-cash-register"></i>
            Pembayaran
        </a>
    </li>


    <li class="{{ Request::is('setoran/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="si si-wallet"></i>
            <span class="sidebar-mini-hide">Setoran</span>
        </a>
        <ul>
            <li>
                <a class="{{ Request::is('setoran/tambah') ? 'active' : null }}" href="{{ route('setoran.sukarela') }}">Simpanan Wajib & Sosial</a>
            </li>
            <li>
                <a class="{{ Request::is('setoran/sukarela') ? 'active' : null }}" href="{{ route('setoran.sukarela') }}">Simpanan Sukarela</a>
            </li>

        </ul>
    </li>
    
    <li class="{{ Request::is('simpanan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="si si-wallet"></i>
            <span class="sidebar-mini-hide">Simpanan</span>
        </a>
        <ul>
            <li class="{{ Request::is('simpanan/koperasi/*') ? 'open' : null }}">
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Koperasi</a>
                <ul>
                    <li>
                        <a class="{{ Request::is('simpanan/koperasi/setoran') ? 'active' : null }}"
                            href="{{ route('simkop.setoran') }}">Setoran Tunai</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('simpanan/koperasi/tunggakan') ? 'active' : null }}"
                            href="{{ route('simkop.tunggakan') }}">Data Tunggakan</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('simpanan/koperasi/riwayat') ? 'active' : null }}"
                            href="{{ route('simkop.riwayat') }}">Riwayat Transaksi</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('simpanan/sukarela/*') ? 'open' : null }}">
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sukarela</a>
                <ul>
                    <li>
                        <a class="{{ Request::is('simpanan/sukarela/setoran') ? 'active' : null }}"
                            href="{{ route('simla.setoran') }}">Setoran Tunai</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('simpanan/sukarela/penarikan') ? 'active' : null }}"
                            href="{{ route('simla.penarikan') }}">Penarikan Tunai</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('simpanan/sukarela/riwayat') ? 'active' : null }}"
                            href="{{ route('simla.riwayat') }}">Riwayat Transaksi</a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>

    <li class="{{ Request::is('pembiayaan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="fas fa-money-bill-wave"></i>
            <span class="sidebar-mini-hide">Pembiayaan</span>
        </a>
        <ul>

            <li class="{{ Request::is('pembiayaan/tunai*') ? 'open' : null }}">
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Tunai</a>
                <ul>
                    <li>
                        <a class="{{ Request::is('pembiayaan/tunai/pengajuan') ? 'active' : null }}"
                            href="{{ route('pmb_tunai.pengajuan') }}">Pengajuan</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('pembiayaan/tunai') ? 'active' : null }}"
                            href="{{ route('pmb_tunai') }}">Daftar Pembiayaan</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('pembiayaan/tunai/penarikan') ? 'active' : null }}"
                            href="{{ route('pmb_tunai.tagihan') }}">Tagihan</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('pembiayaan/tunai/riwayat') ? 'active' : null }}"
                            href="{{ route('pmb_tunai.riwayat') }}">Riwayat Transaksi</a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>

    
    <li class="{{ Request::is('keuangan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bar-chart"></i><span class="sidebar-mini-hide">Keuangan</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('keuangan/bank') ? 'active' : null }}" href="{{ route('bank') }}">Data Bank</a>
            </li>
            <li class="{{ Request::is('keuangan/kas/*', 'keuangan/kas') ? 'open' : null }}">
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Kas</a>
                <ul>
                    <li>
                        <a class="{{ Request::is('keuangan/kas') ? 'active' : null }}" href="{{ route('kas') }}">Data Kas</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('keuangan/kas/pemasukan', 'keuangan/kas/pemasukan/*') ? 'active' : null }}" href="{{ route('kas.income') }}">Pemasukan Kas</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('keuangan/kas/pengeluaran', 'keuangan/kas/pengeluaran/*') ? 'active' : null }}" href="{{ route('kas.expense') }}">Pengeluaran Kas</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('keuangan/kas/transfer', 'keuangan/kas/transfer/*') ? 'active' : null }}" href="{{ route('kas.transfer') }}">Transfer Kas</a>
                    </li>
                </ul>
            </li>
            <li class="{{ Request::is('keuangan/akun/*', 'keuangan/akun') ? 'open' : null }}">
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Akun (Account)</a>
                <ul>
                    <li>
                        <a class="{{ Request::is('keuangan/akun') ? 'active' : null }}" href="{{ route('akun') }}">Kelola Akun</a>
                    </li>
                    <li>
                        <a class="{{ Request::is('keuangan/akun/klasifikasi') ? 'active' : null }}" href="{{ route('akun.klasifikasi') }}">Klasifikasi Akun</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        <a class="{{ Request::is('slider', 'slider/*') ? 'active' : null }}" href="{{ route('slider') }}">
            <i class="si si-picture"></i>Slider</span>
        </a>
    </li>
    <li class="{{ Request::is('laporan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-printer"></i><span class="sidebar-mini-hide">Laporan</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('laporan/simpanan') ? 'active' : null }}" href="{{ route('laporan.simpanan') }}">Kas Simpanan</a>
            </li>
            <li>
                <a class="{{ Request::is('laporan/neraca-saldo') ? 'active' : null }}" href="{{ route('laporan.neraca') }}">Neraca Saldo</a>
            </li>
        </ul>
    </li>
    <li>
        <a class="{{ Request::is('Pengguna') ? 'active' : null }}" href="{{ route('pengguna') }}">
            <i class="fa fa-user-tie"></i>Pengguna
        </a>
    </li>
    <li class="{{ Request::is('mobile-koperasi/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-mobile-alt"></i><span class="sidebar-mini-hide">Mobile Koperasi</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('mobile-koperasi/buku-besar') ? 'active' : null }}" href="{{ route('mobile.anggota') }}">Anggota</a>
            </li>
        </ul>
    </li>
</ul>
