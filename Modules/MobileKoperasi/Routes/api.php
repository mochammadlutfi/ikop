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


Route::post('/login','LoginController@login');

// Route::middleware('auth:api')->get('/mobilekoperasi', function (Request $request) {
    
Route::middleware('auth:api')->namespace('API')->group(function() {
    Route::get('/getSimlaSaldo','SimpananController@getSimlaSaldo');
    Route::get('/getSimpanan','SimpananController@getSimpanan');
});