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

Route::group(['prefix' => 'anggota'], function () {
    Route::redirect('/', 'list');
    Route::get('/list', 'AnggotaController@index')->name('anggota');
    Route::get('/coba', 'AnggotaController@coba')->name('coba');
    
    Route::get('/hapus/{id}', 'AnggotaController@hapus')->name('anggota.hapus');
    
    Route::get('/get_id/{id}', 'AnggotaController@get_id')->name('anggota.get_info');
    Route::post('/select2', 'AnggotaController@select2')->name('anggota.select2');
    
    Route::post('/upadate-profil', 'AnggotaController@updateProfil')->name('anggota.updateProfil');
    Route::post('/upadate-alamat', 'AnggotaController@updateAlamat')->name('anggota.updateAlamat');
    
    Route::group(['prefix' => 'pendaftaran'], function () {
        Route::get('/step-1', 'AnggotaRegisterController@step1')->name('anggota.tambah');
        Route::post('/step-1', 'AnggotaRegisterController@step1Store')->name('anggota.tambah.step1.store');
        
        Route::get('/step-2', 'AnggotaRegisterController@step2')->name('anggota.tambah.step2');
        Route::post('/step-2', 'AnggotaRegisterController@step2Store')->name('anggota.tambah.step2.store');

        Route::get('/step-3', 'AnggotaRegisterController@step3')->name('anggota.tambah.step3');
        Route::post('/step-3', 'AnggotaRegisterController@step3Store')->name('anggota.tambah.step3.store');
    });

    
    Route::get('/detail/{id}', 'AnggotaDetailController@index')->name('anggota.detail');
    Route::get('/detail/{id}/biodata', 'AnggotaDetailController@biodata')->name('anggota.detail.biodata');
    Route::get('/detail/{id}/simpanan', 'AnggotaDetailController@simpanan')->name('anggota.detail.simpanan');
    Route::get('/detail/{id}/transaksi', 'AnggotaDetailController@transaksi')->name('anggota.detail.transaksi');

});