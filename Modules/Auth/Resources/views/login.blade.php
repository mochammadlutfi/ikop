<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <!-- Stylesheets -->
        

        @include('layouts.meta')

        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('css/laravel.app.css') }}">
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                <div class="bg-white">
                    <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                        <!-- Header -->
                        <div class="py-30 px-5 text-center">
                            <a class="" href="{{ url('/') }}">
                                <img src="{{ asset('media/logo/logo_big.png') }}" width="300px">
                            </a>
                            <h2 class="h5 font-w700 mb-0 mt-30">Silakan masuk untuk melanjutkan</h2>
                        </div>
                        <!-- END Header -->

                        <!-- Sign In Form -->
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <form method="POST" id="loginForm" onsubmit="return false">
                                    @csrf
                                    <div class="form-group row mb-2">
                                        <div class="col-12">
                                            <label for="login-type">Username / No Anggota</label>
                                            <input type="text" class="form-control" id="login-type" name="type">
                                            <div class="invalid-feedback" id="error-type"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <div class="col-12">
                                            <label for="login-password">Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" class="form-control" id="login-password" name="password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <a href="javaScript:void(0);"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                                    </span>
                                                </div>
                                                <div class="invalid-feedback" id="error-password"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <div class="col-sm-6 d-sm-flex align-items-center">
                                            <div class="custom-control custom-checkbox mr-auto ml-0 mb-0">
                                                <input type="checkbox" class="custom-control-input" id="login-remember-me" name="remember">
                                                <label class="custom-control-label" for="login-remember-me">Ingat Saya</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-sm-right">
                                            <a class="font-weight-bold" href="#">
                                                Lupa Password?
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-12 text-sm-right">
                                            <button type="submit" class="btn btn-primary btn-block" disabled>
                                                <i class="si si-login mr-10"></i> Masuk
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Sign In Form -->
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
        <script src="{{ asset('js/laravel.app.js') }}"></script>
        <script src="{{ asset('js/laroute.js') }}"></script>
        <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        
        <script src="{{ Module::asset('Auth:Assets/js/login.js') }}"></script>
    </body>
</html>
