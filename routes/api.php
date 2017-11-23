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
$api = app('Dingo\Api\Routing\Router');
$api->version('v1',['namespace' => 'App\Http\Controllers'],function ($api) {
  $api->post('register', 'APIAuthController@register');
  $api->post('login', 'APIAuthController@authenticate');
  $api->post('dokter','APIDoktersController@store');
  $api->post('poli','APIPoliController@store');
  $api->post('jadwal','APIDoktersController@jadwal');
  $api->put('send','APIAuthController@send');
  $api->post('verify','APIAuthController@verify');
  $api->post('generate','APIAuthController@generate');
});

$api->version('v1',['middleware'=> 'api.auth','namespace' => 'App\Http\Controllers'], function($api){
  $api->get('logout','APIAuthController@logout');
  $api->get('test','APIAuthController@test');
  //Check Up
  $api->post('check-up','APICheckUpController@store');
  $api->get('check-up','APICheckUpController@getCheckUp');
  $api->get('data','APIDoktersController@getData');
});

//     Route::get('logout', 'APIAuthController@logout');
//     Route::get('test', 'APIAuthController@test');
