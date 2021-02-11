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


Route::get('/','App\Http\Controllers\VideoController@create')->name('home');
Route::post('/','App\Http\Controllers\VideoController@store');
Route::get('/results','App\Http\Controllers\VideoController@allData')->name('results');
Route::get('/cleared','App\Http\Controllers\VideoController@Trunc')->name('Trunc');

