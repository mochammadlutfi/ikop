(function () {

    var laroute = (function () {

        var routes = {

            absolute: true,
            rootUrl: 'http://localhost/bumaba',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/open","name":"debugbar.openhandler","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@handle"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/clockwork\/{id}","name":"debugbar.clockwork","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@clockwork"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/telescope\/{id}","name":"debugbar.telescope","action":"Barryvdh\Debugbar\Controllers\TelescopeController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/stylesheets","name":"debugbar.assets.css","action":"Barryvdh\Debugbar\Controllers\AssetController@css"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/javascript","name":"debugbar.assets.js","action":"Barryvdh\Debugbar\Controllers\AssetController@js"},{"host":null,"methods":["DELETE"],"uri":"_debugbar\/cache\/{key}\/{tags?}","name":"debugbar.cache.delete","action":"Barryvdh\Debugbar\Controllers\CacheController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"coba","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/anggota","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD","POST","PUT","PATCH","DELETE","OPTIONS"],"uri":"anggota","name":null,"action":"\Illuminate\Routing\RedirectController"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/list","name":"anggota","action":"Modules\Anggota\Http\Controllers\AnggotaController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/coba","name":"coba","action":"Modules\Anggota\Http\Controllers\AnggotaController@coba"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/hapus\/{id}","name":"anggota.hapus","action":"Modules\Anggota\Http\Controllers\AnggotaController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/get_id\/{id}","name":"anggota.get_info","action":"Modules\Anggota\Http\Controllers\AnggotaController@get_id"},{"host":null,"methods":["POST"],"uri":"anggota\/select2","name":"anggota.select2","action":"Modules\Anggota\Http\Controllers\AnggotaController@select2"},{"host":null,"methods":["POST"],"uri":"anggota\/upadate-profil","name":"anggota.updateProfil","action":"Modules\Anggota\Http\Controllers\AnggotaController@updateProfil"},{"host":null,"methods":["POST"],"uri":"anggota\/upadate-alamat","name":"anggota.updateAlamat","action":"Modules\Anggota\Http\Controllers\AnggotaController@updateAlamat"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/pendaftaran\/step-1","name":"anggota.tambah","action":"Modules\Anggota\Http\Controllers\AnggotaRegisterController@step1"},{"host":null,"methods":["POST"],"uri":"anggota\/pendaftaran\/step-1","name":"anggota.tambah.step1.store","action":"Modules\Anggota\Http\Controllers\AnggotaRegisterController@step1Store"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/pendaftaran\/step-2","name":"anggota.tambah.step2","action":"Modules\Anggota\Http\Controllers\AnggotaRegisterController@step2"},{"host":null,"methods":["POST"],"uri":"anggota\/pendaftaran\/step-2","name":"anggota.tambah.step2.store","action":"Modules\Anggota\Http\Controllers\AnggotaRegisterController@step2Store"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/pendaftaran\/step-3","name":"anggota.tambah.step3","action":"Modules\Anggota\Http\Controllers\AnggotaRegisterController@step3"},{"host":null,"methods":["POST"],"uri":"anggota\/pendaftaran\/step-3","name":"anggota.tambah.step3.store","action":"Modules\Anggota\Http\Controllers\AnggotaRegisterController@step3Store"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/detail\/{id}","name":"anggota.detail","action":"Modules\Anggota\Http\Controllers\AnggotaDetailController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/detail\/{id}\/biodata","name":"anggota.detail.biodata","action":"Modules\Anggota\Http\Controllers\AnggotaDetailController@biodata"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/detail\/{id}\/simpanan","name":"anggota.detail.simpanan","action":"Modules\Anggota\Http\Controllers\AnggotaDetailController@simpanan"},{"host":null,"methods":["GET","HEAD"],"uri":"anggota\/detail\/{id}\/transaksi","name":"anggota.detail.transaksi","action":"Modules\Anggota\Http\Controllers\AnggotaDetailController@transaksi"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/auth","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":null,"action":"Modules\Auth\Http\Controllers\LoginController@showLoginForm"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"Modules\Auth\Http\Controllers\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"Modules\Auth\Http\Controllers\LoginController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":"logout","action":"Modules\Auth\Http\Controllers\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset","name":"password.request","action":"Modules\Auth\Http\Controllers\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"password\/email","name":"password.email","action":"Modules\Auth\Http\Controllers\ForgotPasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset\/{token}","name":"password.reset","action":"Modules\Auth\Http\Controllers\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"password\/reset","name":"password.update","action":"Modules\Auth\Http\Controllers\ResetPasswordController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"email\/verify","name":"verification.notice","action":"Modules\Auth\Http\Controllers\VerificationController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"email\/verify\/{id}","name":"verification.verify","action":"Modules\Auth\Http\Controllers\VerificationController@verify"},{"host":null,"methods":["GET","HEAD"],"uri":"email\/resend","name":"verification.resend","action":"Modules\Auth\Http\Controllers\VerificationController@resend"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/cabang","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"cabang","name":"cabang","action":"Modules\Cabang\Http\Controllers\CabangController@index"},{"host":null,"methods":["POST"],"uri":"cabang\/save","name":"cabang.save","action":"Modules\Cabang\Http\Controllers\CabangController@save"},{"host":null,"methods":["GET","HEAD"],"uri":"cabang\/edit\/{id}","name":"cabang.edit","action":"Modules\Cabang\Http\Controllers\CabangController@edit"},{"host":null,"methods":["POST"],"uri":"cabang\/update","name":"cabang.update","action":"Modules\Cabang\Http\Controllers\CabangController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"cabang\/hapus\/{id}","name":"cabang.hapus","action":"Modules\Cabang\Http\Controllers\CabangController@hapus"},{"host":null,"methods":["POST"],"uri":"wilayahSelect","name":"wilayah.jsonSelect","action":"Modules\Cabang\Http\Controllers\WilayahController@jsonSelect"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/dashboard","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"dashboard","name":"dashboard","action":"Modules\Dashboard\Http\Controllers\DashboardController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/keuangan","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas","name":"kas","action":"Modules\Keuangan\Http\Controllers\KasController@index"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/store","name":"kas.store","action":"Modules\Keuangan\Http\Controllers\KasController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/edit\/{id}","name":"kas.edit","action":"Modules\Keuangan\Http\Controllers\KasController@edit"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/update","name":"kas.update","action":"Modules\Keuangan\Http\Controllers\KasController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/hapus\/{id}","name":"kas.delete","action":"Modules\Keuangan\Http\Controllers\KasController@delete"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/select2","name":"kas.select2","action":"Modules\Keuangan\Http\Controllers\KasController@select2"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pemasukan","name":"kas.income","action":"Modules\Keuangan\Http\Controllers\KasIncomeController@index"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/pemasukan\/store","name":"kas.income.store","action":"Modules\Keuangan\Http\Controllers\KasIncomeController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pemasukan\/edit\/{id}","name":"kas.income.edit","action":"Modules\Keuangan\Http\Controllers\KasIncomeController@edit"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/pemasukan\/update","name":"kas.income.update","action":"Modules\Keuangan\Http\Controllers\KasIncomeController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pemasukan\/hapus\/{id}","name":"kas.income.delete","action":"Modules\Keuangan\Http\Controllers\KasIncomeController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pemasukan\/detail\/{id}","name":"kas.income.detail","action":"Modules\Keuangan\Http\Controllers\KasIncomeController@detail"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pengeluaran","name":"kas.expense","action":"Modules\Keuangan\Http\Controllers\KasExpenseController@index"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/pengeluaran\/store","name":"kas.expense.store","action":"Modules\Keuangan\Http\Controllers\KasExpenseController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pengeluaran\/edit\/{id}","name":"kas.expense.edit","action":"Modules\Keuangan\Http\Controllers\KasExpenseController@edit"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/pengeluaran\/update","name":"kas.expense.update","action":"Modules\Keuangan\Http\Controllers\KasExpenseController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pengeluaran\/hapus\/{id}","name":"kas.expense.delete","action":"Modules\Keuangan\Http\Controllers\KasExpenseController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/pengeluaran\/detail\/{id}","name":"kas.expense.detail","action":"Modules\Keuangan\Http\Controllers\KasExpenseController@detail"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/transfer","name":"kas.transfer","action":"Modules\Keuangan\Http\Controllers\KasTransferController@index"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/transfer\/store","name":"kas.transfer.store","action":"Modules\Keuangan\Http\Controllers\KasTransferController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/transfer\/edit\/{id}","name":"kas.transfer.edit","action":"Modules\Keuangan\Http\Controllers\KasTransferController@edit"},{"host":null,"methods":["POST"],"uri":"keuangan\/kas\/transfer\/update","name":"kas.transfer.update","action":"Modules\Keuangan\Http\Controllers\KasTransferController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/transfer\/hapus\/{id}","name":"kas.transfer.delete","action":"Modules\Keuangan\Http\Controllers\KasTransferController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/kas\/transfer\/detail\/{id}","name":"kas.transfer.detail","action":"Modules\Keuangan\Http\Controllers\KasTransferController@detail"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/akun","name":"akun","action":"Modules\Keuangan\Http\Controllers\AkunController@index"},{"host":null,"methods":["POST"],"uri":"keuangan\/akun\/store","name":"akun.store","action":"Modules\Keuangan\Http\Controllers\AkunController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/akun\/edit\/{id}","name":"akun.edit","action":"Modules\Keuangan\Http\Controllers\AkunController@edit"},{"host":null,"methods":["POST"],"uri":"keuangan\/akun\/update","name":"akun.update","action":"Modules\Keuangan\Http\Controllers\AkunController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/akun\/hapus\/{id}","name":"akun.delete","action":"Modules\Keuangan\Http\Controllers\AkunController@delete"},{"host":null,"methods":["POST"],"uri":"keuangan\/akun\/select2","name":"akun.select2","action":"Modules\Keuangan\Http\Controllers\AkunController@select2"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/akun\/klasifikasi","name":"akun.klasifikasi","action":"Modules\Keuangan\Http\Controllers\AkunKlasifikasiController@index"},{"host":null,"methods":["POST"],"uri":"keuangan\/akun\/klasifikasi\/store","name":"akun.klasifikasi.store","action":"Modules\Keuangan\Http\Controllers\AkunKlasifikasiController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/akun\/klasifikasi\/edit\/{id}","name":"akun.klasifikasi.edit","action":"Modules\Keuangan\Http\Controllers\AkunKlasifikasiController@edit"},{"host":null,"methods":["POST"],"uri":"keuangan\/akun\/klasifikasi\/update","name":"akun.klasifikasi.update","action":"Modules\Keuangan\Http\Controllers\AkunKlasifikasiController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"keuangan\/akun\/klasifikasi\/hapus\/{id}","name":"akun.klasifikasi.delete","action":"Modules\Keuangan\Http\Controllers\AkunKlasifikasiController@delete"},{"host":null,"methods":["POST"],"uri":"keuangan\/akun\/klasifikasi\/select2","name":"akun.klasifikasi.select2","action":"Modules\Keuangan\Http\Controllers\AkunKlasifikasiController@select2"},{"host":null,"methods":["GET","HEAD"],"uri":"transaksi","name":"transaksi","action":"Modules\Keuangan\Http\Controllers\TransactionController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/laporan","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"laporan","name":"laporan","action":"Modules\Laporan\Http\Controllers\LaporanController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"laporan\/buku-besar","name":"laporan.buku_besar","action":"Modules\Laporan\Http\Controllers\LaporanController@buku_besar"},{"host":null,"methods":["GET","HEAD"],"uri":"laporan\/simpanan","name":"laporan.simpanan","action":"Modules\Laporan\Http\Controllers\LaporanController@simpanan"},{"host":null,"methods":["GET","HEAD"],"uri":"laporan\/neraca-saldo","name":"laporan.neraca","action":"Modules\Laporan\Http\Controllers\LaporanController@neraca"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/mobilekoperasi","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"mobile-koperasi","name":"mobile.anggota","action":"Modules\MobileKoperasi\Http\Controllers\AnggotaController@index"},{"host":null,"methods":["POST"],"uri":"mobile-koperasi\/store","name":"mobile.anggota.store","action":"Modules\MobileKoperasi\Http\Controllers\AnggotaController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"mobile-koperasi\/edit\/{id}","name":"mobile.anggota.edit","action":"Modules\MobileKoperasi\Http\Controllers\AnggotaController@edit"},{"host":null,"methods":["POST"],"uri":"mobile-koperasi\/update","name":"mobile.anggota.update","action":"Modules\MobileKoperasi\Http\Controllers\AnggotaController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"mobile-koperasi\/hapus\/{id}","name":"mobile.anggota.hapus","action":"Modules\MobileKoperasi\Http\Controllers\AnggotaController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/pembiayaan","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"pembiayaan","name":null,"action":"Modules\Pembiayaan\Http\Controllers\PembiayaanController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/pengguna","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna","name":"pengguna","action":"Modules\Pengguna\Http\Controllers\PenggunaController@index"},{"host":null,"methods":["POST"],"uri":"pengguna\/store","name":"pengguna.store","action":"Modules\Pengguna\Http\Controllers\PenggunaController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna\/edit\/{id}","name":"pengguna.edit","action":"Modules\Pengguna\Http\Controllers\PenggunaController@edit"},{"host":null,"methods":["POST"],"uri":"pengguna\/update","name":"pengguna.update","action":"Modules\Pengguna\Http\Controllers\PenggunaController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna\/hapus\/{id}","name":"pengguna.hapus","action":"Modules\Pengguna\Http\Controllers\PenggunaController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/simpanan","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/setoran","name":"simkop.setoran","action":"Modules\Simpanan\Http\Controllers\KoperasiController@setoran"},{"host":null,"methods":["POST"],"uri":"simpanan\/koperasi\/store","name":"simkop.store","action":"Modules\Simpanan\Http\Controllers\KoperasiController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/edit\/{id}","name":"simkop.edit","action":"Modules\Simpanan\Http\Controllers\KoperasiController@edit"},{"host":null,"methods":["POST"],"uri":"simpanan\/koperasi\/update","name":"simkop.update","action":"Modules\Simpanan\Http\Controllers\KoperasiController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/riwayat","name":"simkop.riwayat","action":"Modules\Simpanan\Http\Controllers\KoperasiController@riwayat"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/invoice\/{id}","name":"simkop.invoice","action":"Modules\Simpanan\Http\Controllers\KoperasiController@invoice"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/invoice-print\/{id}","name":"simkop.invoice.print","action":"Modules\Simpanan\Http\Controllers\KoperasiController@invoice_print"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/tunggakan","name":"simkop.tunggakan","action":"Modules\Simpanan\Http\Controllers\KoperasiController@tunggakan"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/koperasi\/tunggakan\/detail\/{id}","name":"simkop.tunggakan.detail","action":"Modules\Simpanan\Http\Controllers\KoperasiController@tunggakan_detail"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/sukarela\/setoran","name":"simla.setoran","action":"Modules\Simpanan\Http\Controllers\SukarelaController@setoran"},{"host":null,"methods":["POST"],"uri":"simpanan\/sukarela\/setoran","name":"simla.store","action":"Modules\Simpanan\Http\Controllers\SukarelaController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/sukarela\/penarikan","name":"simla.penarikan","action":"Modules\Simpanan\Http\Controllers\SukarelaController@penarikan"},{"host":null,"methods":["POST"],"uri":"simpanan\/sukarela\/penarikan","name":"simla.penarikan_store","action":"Modules\Simpanan\Http\Controllers\SukarelaController@penarikan_store"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/sukarela\/riwayat","name":"simla.riwayat","action":"Modules\Simpanan\Http\Controllers\SukarelaController@riwayat"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/sukarela\/edit\/{id}","name":"simla.edit","action":"Modules\Simpanan\Http\Controllers\SukarelaController@edit"},{"host":null,"methods":["POST"],"uri":"simpanan\/sukarela\/update","name":"simla.update","action":"Modules\Simpanan\Http\Controllers\SukarelaController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/sukarela\/invoice\/{id}","name":"simla.invoice","action":"Modules\Simpanan\Http\Controllers\SukarelaController@invoice"},{"host":null,"methods":["GET","HEAD"],"uri":"simpanan\/sukarela\/invoice-print\/{id}","name":"simla.invoice.print","action":"Modules\Simpanan\Http\Controllers\SukarelaController@invoice_print"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/wilayah","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"wilayah","name":null,"action":"Modules\Wilayah\Http\Controllers\WilayahController@index"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

