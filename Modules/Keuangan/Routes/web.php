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

Route::prefix('keuangan')->group(function() {

    Route::group(['prefix' => 'kas'], function () {
        Route::get('/', 'KasController@index')->name('kas');
        Route::post('/store','KasController@store')->name('kas.store');
        Route::get('/edit/{id}','KasController@edit')->name('kas.edit');
        Route::post('/update','KasController@update')->name('kas.update');
        Route::get('/hapus/{id}','KasController@delete')->name('kas.delete');
        Route::post('/select2','KasController@select2')->name('kas.select2');

        Route::group(['prefix' => 'pemasukan'], function () {
            Route::get('/', 'KasIncomeController@index')->name('kas.income');
            Route::post('/store','KasIncomeController@store')->name('kas.income.store');
            Route::get('/edit/{id}','KasIncomeController@edit')->name('kas.income.edit');
            Route::post('/update','KasIncomeController@update')->name('kas.income.update');
            Route::get('/hapus/{id}','KasIncomeController@delete')->name('kas.income.delete');
            Route::get('/detail/{id}','KasIncomeController@detail')->name('kas.income.detail');
        });

        Route::group(['prefix' => 'pengeluaran'], function () {
            Route::get('/', 'KasExpenseController@index')->name('kas.expense');
            Route::post('/store','KasExpenseController@store')->name('kas.expense.store');
            Route::get('/edit/{id}','KasExpenseController@edit')->name('kas.expense.edit');
            Route::post('/update','KasExpenseController@update')->name('kas.expense.update');
            Route::get('/hapus/{id}','KasExpenseController@delete')->name('kas.expense.delete');
            Route::get('/detail/{id}','KasExpenseController@detail')->name('kas.expense.detail');
        });

        Route::group(['prefix' => 'transfer'], function () {
            Route::get('/', 'KasTransferController@index')->name('kas.transfer');
            Route::post('/store','KasTransferController@store')->name('kas.transfer.store');
            Route::get('/edit/{id}','KasTransferController@edit')->name('kas.transfer.edit');
            Route::post('/update','KasTransferController@update')->name('kas.transfer.update');
            Route::get('/hapus/{id}','KasTransferController@delete')->name('kas.transfer.delete');
            Route::get('/detail/{id}','KasTransferController@detail')->name('kas.transfer.detail');
        });
        
    });

    Route::prefix('bank')->group(function() {
        Route::get('/', 'BankController@index')->name('bank');
        Route::post('/store','BankController@store')->name('bank.store');
        Route::get('/edit/{id}','BankController@edit')->name('bank.edit');
        Route::post('/update','BankController@update')->name('bank.update');
        Route::get('/hapus/{id}','BankController@delete')->name('bank.delete');
    });

    Route::group(['prefix' => 'akun'], function () {
        Route::get('/', 'AkunController@index')->name('akun');
        Route::post('/store','AkunController@store')->name('akun.store');
        Route::get('/edit/{id}','AkunController@edit')->name('akun.edit');
        Route::post('/update','AkunController@update')->name('akun.update');
        Route::get('/hapus/{id}','AkunController@delete')->name('akun.delete');
        Route::post('/select2','AkunController@select2')->name('akun.select2');

        Route::group(['prefix' => 'klasifikasi'], function () {
            Route::get('/', 'AkunKlasifikasiController@index')->name('akun.klasifikasi');
            Route::post('/store','AkunKlasifikasiController@store')->name('akun.klasifikasi.store');
            Route::get('/edit/{id}','AkunKlasifikasiController@edit')->name('akun.klasifikasi.edit');
            Route::post('/update','AkunKlasifikasiController@update')->name('akun.klasifikasi.update');
            Route::get('/hapus/{id}','AkunKlasifikasiController@delete')->name('akun.klasifikasi.delete');
            Route::post('/select2','AkunKlasifikasiController@select2')->name('akun.klasifikasi.select2');
        });
    });
});


Route::group(['prefix' => 'transaksi'], function () {
    Route::get('/', 'TransactionController@index')->name('transaksi');
});