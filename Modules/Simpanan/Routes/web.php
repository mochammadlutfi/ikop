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
Route::group(['prefix' => 'simpanan'], function () {

    
    Route::group(['prefix' => 'koperasi'], function () {

        Route::get('/setoran', 'KoperasiController@setoran')->name('simkop.setoran');
        Route::post('/store', 'KoperasiController@store')->name('simkop.store');

        Route::get('/edit/{id}', 'KoperasiController@edit')->name('simkop.edit');
        Route::post('/update', 'KoperasiController@update')->name('simkop.update');
        
        Route::get('/riwayat', 'KoperasiController@riwayat')->name('simkop.riwayat');
        Route::get('/invoice/{id}', 'KoperasiController@invoice')->name('simkop.invoice');
        Route::get('/invoice-print/{id}', 'KoperasiController@invoice_print')->name('simkop.invoice.print');
        
        Route::get('/tunggakan', 'KoperasiController@tunggakan')->name('simkop.tunggakan');
        Route::get('/tunggakan/detail/{id}', 'KoperasiController@tunggakan_detail')->name('simkop.tunggakan.detail');
    });

    Route::group(['prefix' => 'sukarela'], function () {
        Route::get('/setoran', 'SukarelaController@setoran')->name('simla.setoran');
        Route::post('/setoran', 'SukarelaController@store')->name('simla.store');

        Route::get('/penarikan', 'SukarelaController@penarikan')->name('simla.penarikan');
        Route::post('/penarikan', 'SukarelaController@penarikan_store')->name('simla.penarikan_store');
        
        Route::get('/riwayat', 'SukarelaController@riwayat')->name('simla.riwayat');

        
        Route::get('/edit/{id}', 'SukarelaController@edit')->name('simla.edit');
        Route::post('/update', 'SukarelaController@update')->name('simla.update');

        Route::get('/invoice/{id}', 'SukarelaController@invoice')->name('simla.invoice');
        Route::get('/invoice-print/{id}', 'SukarelaController@invoice_print')->name('simla.invoice.print');
    });
});
