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

Route::prefix('pengguna')->group(function() {
    Route::get('/', 'PenggunaController@index')->name('pengguna');
    Route::post('/store','PenggunaController@store')->name('pengguna.store');
    Route::get('/edit/{id}','PenggunaController@edit')->name('pengguna.edit');
    Route::post('/update','PenggunaController@update')->name('pengguna.update');
    Route::get('/hapus/{id}','PenggunaController@hapus')->name('pengguna.hapus');
});
