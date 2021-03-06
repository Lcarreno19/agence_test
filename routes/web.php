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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

    // Route for Resource Desempenho
    Route::resource('desempenho', 'App\Http\Controllers\DesempenhoController', ['except' => ['show']]);
	Route::put('desempenho/filter', ['as' => 'desempenho.filter', 'uses' => 'App\Http\Controllers\DesempenhoController@filter']);

});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

// Busquedas Ajax
Route::post('/consultar-relatorio', [App\Http\Controllers\DesempenhoController::class, 'consultarRelatorio'])->name('consultar-relatorio');

Route::post('/consultar-pizza', [App\Http\Controllers\DesempenhoController::class, 'consultarPizza'])->name('consultar-pizza');

Route::post('/consultar-bar', [App\Http\Controllers\DesempenhoController::class, 'consultarBar'])->name('consultar-bar');

