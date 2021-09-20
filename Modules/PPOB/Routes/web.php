<?php
use Illuminate\Support\Facades\Http;
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

Route::post('ppob/callback', 'PPOBController@index')->name('ppob.calback');

Route::prefix('ppob')->group(function() {
    // $body['commands'] = "pricelist";
    // $body['username'] = "089656466525";
    // $body['sign'] = "841413dc0a2c800c45f63d7faa1bfbdb";
    // $body['status'] = "active";
    // Route::get('/', 'PPOBController@index');
    // $status = PPOB::topup(new Pulsa('082112345678', 50000), 'ref123');
    // $username   = "089656466525";
    // $apiKey   = "3965e5b9f739b3ac";
    // $ref = "PBT0012";
    // $signature  = md5($username.$apiKey.$ref);
    // $json = array(
    //     'commands' => 'topup',
    //     'username' => $username,
    //     'sign' => $signature,
    // );
    // $json = array_merge($json, [
    //     "ref_id"      => "PBT0012",
    //     "hp"          => "081777721115",
    //     "pulsa_code"  => "xld25000"
    // ]);
    // dd($json);

    // $json = '{
    //         "commands" : "pricelist",
    //         "username" : "089656466525",
    //         "sign"     : "841413dc0a2c800c45f63d7faa1bfbdb"
    //         }';

    // $url = "https://testprepaid.mobilepulsa.net/v1/legacy/index/pulsa/telkomsel";

    // $ch  = curl_init();
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // $data = curl_exec($ch);
    // curl_close($ch);

    // dd(json_decode($data));
    
    // $url = "https://testprepaid.mobilepulsa.net/v1/legacy/index/";
    // $response = Http::withHeaders([
    //     'Content-Type' => 'application/json',
    // ])->post($url, $json);
    // dd(json_decode($response));
});
