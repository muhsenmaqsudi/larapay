<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('pay', 'ZarinpalController@pay');
//Route::get('verify', 'ZarinpalController@verify');

Route::get('purchase', 'PaymentController@purchase');
Route::get('verify', 'PaymentController@verify');
