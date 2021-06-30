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

Route::prefix('mobile-koperasi')->group(function() {
    Route::get('/', 'AnggotaController@index')->name('mobile.anggota');
    Route::post('/store','AnggotaController@store')->name('mobile.anggota.store');
    Route::get('/edit/{id}','AnggotaController@edit')->name('mobile.anggota.edit');
    Route::post('/update','AnggotaController@update')->name('mobile.anggota.update');
    Route::get('/hapus/{id}','AnggotaController@hapus')->name('mobile.anggota.hapus');
});

// Route::prefix('api')->group(function() {
// });
// Route::post('/logout','API/LoginController@logout')->name('logout');
// Route::prefix('mobile-koperasi')->group(function() {
//     Route::get('/', 'AnggotaController@index')->name('mobile.anggota');
//     Route::post('/store','AnggotaController@store')->name('mobile.anggota.store');
//     Route::get('/edit/{id}','AnggotaController@edit')->name('mobile.anggota.edit');
//     Route::post('/update','AnggotaController@update')->name('mobile.anggota.update');
//     Route::get('/hapus/{id}','AnggotaController@hapus')->name('mobile.anggota.hapus');
// });
