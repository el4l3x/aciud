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

/*Route::get('/', function () {
    return view('auth.login');
});*/

//Route::get('/', 'HomeController@index')->name('home');

Route::get('/', 'SolicitudController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/chpass', 'HomeController@chpass')->name('chpass');
Route::post('/updatepass', 'HomeController@updatepass')->name('updatepass');

Route::resource('solicitudes', 'SolicitudController');

