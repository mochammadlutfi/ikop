<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

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

Route::get('/coba', function () {
    // Role::create(['guard_name' => 'admin', 'name' => 'ketua']);
    // Role::create(['guard_name' => 'admin', 'name' => 'sekretaris']);
    // Role::create(['guard_name' => 'admin', 'name' => 'bendahara']);
    auth()->guard('admin')->user()->assignRole('bendahara');
});
// Route::namespace('Auth')->group(function(){
// });


