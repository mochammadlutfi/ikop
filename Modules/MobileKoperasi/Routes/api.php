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
    
    
    
    Route::prefix('simpanan')->group(function() {
        Route::get('/','SimpananController@index');
        Route::get('/{slug}','SimpananController@detail');
        Route::get('/{slug}/riwayat/','SimpananController@riwayat');
    });

    Route::prefix('tagihan')->group(function() {
        Route::get('/simpanan','TagihanController@simpanan');
    });

    Route::prefix('simla')->group(function() {
        Route::get('/','SukarelaController@saldo');
        Route::get('/riwayat','SukarelaController@riwayat');
    });

    Route::prefix('transaksi')->group(function() {
        Route::get('{slug}/{no_transaksi}','TransaksiController@detail');
    });

});