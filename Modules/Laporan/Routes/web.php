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

Route::prefix('laporan')->group(function() {
    Route::get('/', 'LaporanController@index')->name('laporan');
    Route::get('/buku-besar','LaporanController@buku_besar')->name('laporan.buku_besar');
    Route::get('/simpanan','LaporanController@simpanan')->name('laporan.simpanan');
    Route::get('/neraca-saldo','LaporanController@neraca')->name('laporan.neraca');
});
