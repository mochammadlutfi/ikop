<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;
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
    // auth()->guard('admin')->user()->assignRole('bendahara');
    $phone = "+6289656466525";
    $token = getenv("TWILIO_AUTH_TOKEN");
    $twilio_sid = getenv("TWILIO_SID");
    $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
    $twilio = new Client($twilio_sid, $token);
    $twilio->verify->v2->services($twilio_verify_sid)
        ->verifications
        ->create($phone, "sms");
});
// Route::namespace('Auth')->group(function(){
// });


