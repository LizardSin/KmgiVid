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


Route::view('/', 'videos.create2')->name('home');
/*Route::get('/','App\Http\Controllers\VideoController@try123');*/
Route::post('/','App\Http\Controllers\VideoController@init');
Route::post('/act','App\Http\Controllers\VideoController@init2');

Route::get('/cleared','App\Http\Controllers\VideoController@Trunc')->name('Trunc');
Route::get('/results', 'App\Http\Controllers\VideoController@AllResults')->name('AllResults');



/*Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/
