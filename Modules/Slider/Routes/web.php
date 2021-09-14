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

Route::group(['prefix' => 'slider'], function(){
    Route::get('/', 'SliderController@index')->name('slider');
    Route::get('/tambah', 'SliderController@tambah')->name('slider.tambah');
    Route::post('/simpan','SliderController@simpan')->name('slider.simpan');
    Route::get('/edit/{id}','SliderController@edit')->name('slider.edit');
    Route::post('/update','SliderController@update')->name('slider.update');
    Route::get('/hapus/{id}','SliderController@hapus')->name('slider.hapus');
});