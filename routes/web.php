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

Route::get('/', 'SolicitudController@index')->name('inicio');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/chpass', 'HomeController@chpass')->name('chpass');
Route::post('/updatepass', 'HomeController@updatepass')->name('updatepass');

//SOLICITUDS
Route::resource('solicitudes', 'SolicitudController');
Route::get('/solicitudes/create/{tipo}', 'SolicitudController@createinst');
Route::post('/solicitudes/store/terceros', 'SolicitudController@storeter')->name('storet');
Route::post('/solicitudes/store/instituciones', 'SolicitudController@storeins')->name('storei');
Route::post('/solicitudes/rf', 'SolicitudController@rf');
Route::post('/solicitudes/status/{id}', 'SolicitudController@status');
Route::get('/graficos/total', 'SolicitudController@graficast')->name('graficast');
Route::get('/graficos/status', 'SolicitudController@graficass');
Route::get('/graficos/tipo', 'SolicitudController@graficasti');
Route::post('/solicitudes/filter', 'SolicitudController@filter');
Route::post('/solicitudes/listar', 'SolicitudController@listar')->name('pdf');

