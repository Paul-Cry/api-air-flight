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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('airport', "AirportController@search");
Route::get('flight', "FlightController@search");
Route::post('booking', "BookingController@booking");
Route::get('booking/{code}', "BookingController@bookingInfo");
Route::get('booking/{code}/seat', "BookingController@seatInfo");
Route::patch('booking/{code}/seat', "BookingController@changePlace");



//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//
//Route::get('/test', function () {
//    return 'my first route';
//});
//
//Route::get('/users', 'UserController@index');
//Route::post('/users', 'UserController@store');
//Route::post('/login', 'UserController@login');
//Route::middleware('auth:api')->post('/logout', 'UserController@logout');
//
//
//Route::prefix('categories')->group(function () {
//    Route::get('/', 'CategoryController@index');
//    Route::get('/{category}', 'CategoryController@show');
//    Route::middleware('auth:api')->delete('/{category}', 'CategoryController@destroy');
//    Route::middleware('auth:api')->post('/', 'CategoryController@store');
//    Route::middleware('auth:api')->patch('/{category}', 'CategoryController@update');
//
//    Route::prefix('/{category}/products')->group(function (){
//
//        Route::get('/', 'ProductController@index');
//        Route::get('/{product}', 'ProductController@show');
//        Route::middleware('auth:api')->post('/','ProductController@store');
//        Route::middleware('auth:api')->patch('/{product}','ProductController@update');
//        Route::middleware('auth:api')->delete('/{product}','ProductController@destroy');
//
//    });
//
//
//});
