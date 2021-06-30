<!doctype html>
<html lang="en" class="no-focus">
    <head>
        @include('layouts.meta')

        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('css/laravel.app.css') }}">

        <!-- Stylesheets -->
        @yield('styles')
        <!-- END Stylesheets -->

        <!-- Currency setting -->
        <input type="hidden" id="p_code" value="IDR">
        <input type="hidden" id="p_symbol" value="Rp">
        <input type="hidden" id="p_thousand" value=".">
        <input type="hidden" id="p_decimal" value=",">
        <input type="hidden" id="__code" value="IDR">
        <input type="hidden" id="__symbol" value="Rp">
        <input type="hidden" id="__thousand" value=".">
        <input type="hidden" id="__decimal" value=",">
        <input type="hidden" id="__symbol_placement" value="before">
        <input type="hidden" id="__precision" value="0">
        <input type="hidden" id="__quantity_precision" value="0">
    </head>
    <body>

        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header main-content-boxed">

            @include('layouts.sidebar')
            <!-- END Sidebar -->

            <!-- Header -->
            @include('layouts.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                @yield('content')
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="opacity-0">
                <div class="content py-20 font-size-xs clearfix">
                    <div class="float-right">
                        Crafted with <i class="fa fa-heart text-pulse"></i> by <a class="font-w600" href="#" target="_blank">Pintasku</a>
                    </div>
                    <div class="float-left">
                        <a class="font-w600" href="" target="_blank">I-KOP BUMABA</a> &copy; <span class="js-year-copy">2020</span>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <script src="{{ asset('js/laravel.app.js') }}"></script>
        <script src="{{ asset('js/laroute.js') }}"></script>
        <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
        <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="{{ asset('js/functions.js') }}"></script>
        <script src="{{ asset('js/accounting.min.js') }}"></script>
        <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $('.dataTables_filter input[type="search"]').
        // attr('placeholder','Search in this blog ....').
        // css({'width':'500px','display':'inline-block'});
        // $.extend( true, $.fn.dataTable.defaults, {
        //     "responsive": true,
        //     "pageLength": 20,
        //     "lengthChange": false,
        //     "language": {
        //         'loadingRecords': '&nbsp;',
        //         "sEmptyTable":	 "Tidak ada data yang tersedia pada tabel ini",
        //         "sProcessing":   '<div class="spinner-grow text-primary pt-25" role="status"><span class="sr-only">Loading...</span></div>',
        //         "sLengthMenu":   "Tampilkan _MENU_",
        //         "sZeroRecords":  "Tidak ditemukan data yang sesuai",
        //         "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        //         "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
        //         "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
        //         "sInfoPostFix":  "",
        //         "sSearch":       "Cari:",
        //         "sUrl":          "",
        //         "oPaginate": {
        //             "sFirst":    "Pertama",
        //             "sPrevious": "Sebelumnya",
        //             "sNext":     "Selanjutnya",
        //             "sLast":     "Terakhir"
        //         }
        //     },
        // });
        </script>
        <!-- Laravel Scaffolding JS -->
        @stack('scripts')
    </body>
</html>
