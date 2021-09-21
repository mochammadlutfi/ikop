<?php

use Illuminate\Support\Facades\Route;
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
    // Role::create(['guard_name' => 'admin', 'name' => 'ketua']);
    // Role::create(['guard_name' => 'admin', 'name' => 'sekretaris']);
    // Role::create(['guard_name' => 'admin', 'name' => 'bendahara']);
    // auth()->guard('admin')->user()->assignRole('bendahara');
    $phone = "+6289656466525";
    $token = getenv("TWILIO_AUTH_TOKEN");
    $twilio_sid = getenv("TWILIO_SID");
    $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    $twilio = new Client($twilio_sid, $token);
    $twilio->verify->v2->services($twilio_verify_sid)
        ->verifications
        ->create($phone, "sms");
});

Route::group(['prefix' => 'setoran'], function () {

    Route::get('/sukarela', 'SetoranController@sukarela')->name('setoran.sukarela');
    // Route::post('/store', 'KoperasiController@store')->name('simkop.store');

    // Route::get('/edit/{id}', 'KoperasiController@edit')->name('simkop.edit');
    // Route::post('/update', 'KoperasiController@update')->name('simkop.update');
    
    // Route::get('/riwayat', 'KoperasiController@riwayat')->name('simkop.riwayat');
    // Route::get('/invoice/{id}', 'KoperasiController@invoice')->name('simkop.invoice');
    // Route::get('/invoice-print/{id}', 'KoperasiController@invoice_print')->name('simkop.invoice.print');
    
    // Route::get('/tunggakan', 'KoperasiController@tunggakan')->name('simkop.tunggakan');
    // Route::get('/tunggakan/detail/{id}', 'KoperasiController@tunggakan_detail')->name('simkop.tunggakan.detail');
});


// Route::namespace('Auth')->group(function(){
// });


