<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login','API\AuthController@login');

// Route::middleware('auth:api')->get('/mobilekoperasi', function (Request $request) {
    
Route::middleware('auth:api')->namespace('API')->group(function() {
    Route::post('/setup-pin','AuthController@setup_pin');
    Route::post('/access','AuthController@pin_access');

    Route::get('/slider','SliderController@index');

    Route::prefix('anggota')->group(function() {
        Route::get('/','AnggotaController@detail');
        // Route::get('/detail/{id}','TransaksiController@detail');
    });
    
    Route::prefix('simpanan')->group(function() {
        Route::get('/','SimpananController@index');
        Route::get('/{slug}','SimpananController@detail');
        Route::get('/{slug}/riwayat/','SimpananController@riwayat');
    });

    Route::prefix('pembiayaan')->group(function() {
        Route::get('/','PembiayaanController@index');
        Route::get('/{slug}','PembiayaanController@list_detail');
        Route::get('/{slug}/riwayat/','PembiayaanController@riwayat');
        Route::post('/pengajuan','PembiayaanController@pengajuan');
        Route::get('/{slug}/detail/{id}','PembiayaanController@detail');
        Route::get('/{slug}/tagihan','PembiayaanController@tagihan');
    });

    Route::prefix('payment')->group(function() {
        Route::post('/','PaymentController@index');
        Route::get('/bank','PaymentController@bank');
        Route::post('/confirm','PaymentController@confirm');
        Route::get('/detail/{id}','PaymentController@detail');
    });

    Route::prefix('tagihan')->group(function() {
        Route::get('/simpanan','TagihanController@simpanan');
        Route::get('/simpanan/{slug}','TagihanController@detail');
    });

    Route::prefix('simla')->group(function() {
        Route::get('/','SukarelaController@saldo');
        Route::get('/riwayat','SukarelaController@riwayat');
        Route::post('/topup','SukarelaController@topup');
        Route::post('/transfer','SukarelaController@transfer');
        Route::post('/confirm','SukarelaController@confirm');
    });

    Route::prefix('transaksi')->group(function() {
        Route::get('/','TransaksiController@index');
        Route::get('/detail/{id}','TransaksiController@detail');
    });


    Route::prefix('ppob')->group(function() {
        Route::get('/','PPOBController@index');
        Route::post('/payment','PPOBController@payment');
        Route::post('/cek-tagihan','PPOBController@cekTagihan');
    });

});