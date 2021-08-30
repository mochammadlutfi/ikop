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
        <script src="{{ asset('js/functions.js') }}"></script>
        <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (x, status, error) {
            if (x.status == 403) {
                alert("Sorry, your session has expired. Please login again to continue");
                window.location.href ="/login";
            }
            else {
                alert("An error occurred: " + status + "nError: " + error);
            }
        }
        });
        </script>
        @stack('scripts')
        <script src="{{ asset('js/common.js') }}"></script>
    </body>
</html>
