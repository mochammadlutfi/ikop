<?php
use App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/fix', function () {
    Artisan::call('key:generate');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('laroute:generate');
    dd(Artisan::output());
 });
 

Route::get('/coba', function () {
    $phone = "+6289656466525";
    $token = getenv("TWILIO_AUTH_TOKEN");
    $twilio_sid = getenv("TWILIO_SID");
    $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    $twilio = new Client($twilio_sid, $token);
    $twilio->verify->v2->services($twilio_verify_sid)
        ->verifications
        ->create($phone, "sms");
});

Route::get('/notif', function () {
    $key = "AAAALs0-zPY:APA91bFJ47VmfIpZxWBAP5_bxlFQmrGj5cTUS_LDDqL7RHJZxbTu1WwY4WkAHjQvyzx5CKB0qmw4BapuqgkBCE4Q98oCBrUd5bsJWBVC-rFroC7N4BzbvZxYhs1u5AGAisU-Eot8AN60";
    $fcm_token = "eS_WyfvLSPehRJlWDfoQh8:APA91bHrwSAhmEcW_wEwsPohYx2T5ZNTML_0sgbduf6HChkgiCKQjAUiFwYqVja4EDJn7tPLUusNIKdUA3X5dILLWlF0XNJA_h6Xicpu8RowBiagpBiF1-u1Pqcey-CkA-tCdGukkzjV";
    $url = "https://fcm.googleapis.com/fcm/send";
    $header = ["authorization: key=" . $key . "",
        "content-type: application/json",
    ];

    $postdata = '{
        "to" : "' . $fcm_token . '",
        "data" : {
            "title":"Hanya Test",
            "body" : "Ini Deskripsi",
            "transaksi_id":"458",
            "is_read": 0
            }
    }';

    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);
    // close handle to release resources
    curl_close($ch);

    return $result;
});

Route::group(['prefix' => 'setoran'], function () {

    Route::get('/sukarela', 'SetoranController@sukarela')->name('setoran.sukarela');
    Route::get('/wajib', 'SetoranController@wajib')->name('setoran.wajib');
    // Route::post('/store', 'KoperasiController@store')->name('simkop.store');

    // Route::get('/edit/{id}', 'KoperasiController@edit')->name('simkop.edit');
    // Route::post('/update', 'KoperasiController@update')->name('simkop.update');
    
    // Route::get('/riwayat', 'KoperasiController@riwayat')->name('simkop.riwayat');
    // Route::get('/invoice/{id}', 'KoperasiController@invoice')->name('simkop.invoice');
    // Route::get('/invoice-print/{id}', 'KoperasiController@invoice_print')->name('simkop.invoice.print');
    
    // Route::get('/tunggakan', 'KoperasiController@tunggakan')->name('simkop.tunggakan');
    // Route::get('/tunggakan/detail/{id}', 'KoperasiController@tunggakan_detail')->name('simkop.tunggakan.detail');
});


Route::group(['prefix' => 'pembayaran'], function () {
    Route::get('/', 'PembayaranController@index')->name('pembayaran');
    Route::get('/detail/{id}', 'PembayaranController@detail')->name('pembayaran.detail');
    Route::post('/action/{id}', 'PembayaranController@action')->name('pembayaran.action');
});

Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function () {

    Route::prefix('cabang')->group(function() {
        Route::get('/', 'CabangController@index')->name('cabang');
        Route::post('/save','CabangController@save')->name('cabang.save');
        Route::get('/edit/{id}','CabangController@edit')->name('cabang.edit');
        Route::post('/update','CabangController@update')->name('cabang.update');
        Route::get('/hapus/{id}','CabangController@hapus')->name('cabang.hapus');
    });

    Route::group(['prefix' => 'slider'], function(){
        Route::get('/', 'SliderController@index')->name('slider');
        Route::get('/tambah', 'SliderController@tambah')->name('slider.tambah');
        Route::post('/simpan','SliderController@simpan')->name('slider.simpan');
        Route::get('/edit/{id}','SliderController@edit')->name('slider.edit');
        Route::post('/update','SliderController@update')->name('slider.update');
        Route::get('/hapus/{id}','SliderController@hapus')->name('slider.hapus');
    });

    Route::post('wilayahSelect', 'WilayahController@jsonSelect')->name('wilayah.jsonSelect');

    Route::prefix('user')->group(function() {
        Route::get('/', 'UserController@index')->name('settings.user');
        Route::post('/store','UserController@store')->name('settings.user.store');
        Route::get('/edit/{id}','UserController@edit')->name('settings.user.edit');
        Route::post('/update','UserController@update')->name('settings.user.update');
        Route::get('/hapus/{id}','UserController@delete')->name('settings.user.delete');
    });
});

