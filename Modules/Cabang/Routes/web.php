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

Route::prefix('cabang')->group(function() {
    Route::get('/', 'CabangController@index')->name('cabang');
    Route::post('/save','CabangController@save')->name('cabang.save');
    Route::get('/edit/{id}','CabangController@edit')->name('cabang.edit');
    Route::post('/update','CabangController@update')->name('cabang.update');
    Route::get('/hapus/{id}','CabangController@hapus')->name('cabang.hapus');
});


Route::post('wilayahSelect', 'WilayahController@jsonSelect')->name('wilayah.jsonSelect');
