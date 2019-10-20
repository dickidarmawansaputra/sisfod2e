<?php

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

Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::view('dashboard', 'app.dashboard')->name('dashboard')->middleware('role:admin');
Route::view('kirimsurat', 'opd.index')->name('kirim-surat');

Route::middleware('auth')->group(function() {
	Route::get('upload-surat', 'SuratController@index')->name('upload-surat');
	Route::post('upload-surat/simpan', 'SuratController@store')->name('upload-surat.store');
	Route::post('move/server', 'SuratController@moveFileToServer')->name('move.server');

	Route::get('config', 'ConfigController@index')->name('config');
	Route::post('config/store', 'ConfigController@config')->name('config.store');


	Route::get('user', 'UserController@index')->name('user');
	Route::get('user/data', 'UserController@data')->name('user.data');
	Route::post('user/store', 'UserController@store')->name('user.store');
	Route::post('user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
});
