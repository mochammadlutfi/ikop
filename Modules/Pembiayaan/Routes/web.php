<?php

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


Route::prefix('pembiayaan')->group(function() {

    Route::group(['prefix' => 'tunai'], function () {
        
        Route::get('/', 'PmbTunaiController@index')->name('pmb_tunai');
        Route::get('/data', 'PmbTunaiController@data')->name('pmb_tunai.data');
        Route::get('/detail/{id}', 'PmbTunaiController@show')->name('pmb_tunai.detail');

        Route::group(['prefix' => 'pengajuan'], function () {
            Route::get('/', 'PengajuanPmbTunaiController@index')->name('pmb_tunai.pengajuan');
            Route::get('/data', 'PengajuanPmbTunaiController@data')->name('pmb_tunai.pengajuan.data');
            Route::get('/tambah', 'PengajuanPmbTunaiController@create')->name('pmb_tunai.pengajuan.create');
            Route::post('/store', 'PengajuanPmbTunaiController@store')->name('pmb_tunai.pengajuan.store');
            Route::post('/update', 'PengajuanPmbTunaiController@tagihan')->name('pmb_tunai.pengajuan.update');
            Route::get('/detail/{id}', 'PengajuanPmbTunaiController@show')->name('pmb_tunai.pengajuan.detail');
            Route::post('/action/{id}', 'PengajuanPmbTunaiController@action')->name('pmb_tunai.pengajuan.action');
        });
        
        Route::get('/tagihan', 'PmbTunaiController@tagihan')->name('pmb_tunai.tagihan');
        Route::post('/bayar', 'PmbTunaiController@bayar')->name('pmb_tunai.bayar');

        Route::get('/edit/{id}', 'PmbTunaiController@edit')->name('pmb_tunai.edit');
        Route::post('/update', 'PmbTunaiController@update')->name('pmb_tunai.update');
        
        Route::get('/riwayat', 'PmbTunaiController@riwayat')->name('pmb_tunai.riwayat');
        Route::get('/invoice/{id}', 'PmbTunaiController@invoice')->name('pmb_tunai.invoice');
        Route::get('/invoice-print/{id}', 'PmbTunaiController@invoice_print')->name('pmb_tunai.invoice.print');
        
        Route::get('/tunggakan', 'PmbTunaiController@tunggakan')->name('pmb_tunai.tunggakan');
        Route::get('/tunggakan/detail/{id}', 'PmbTunaiController@tunggakan_detail')->name('pmb_tunai.tunggakan.detail');
    });

    

});
