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
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::middleware('auth')->group(function() {
	// Route::view('dashboard', 'app.dashboard')->name('dashboard')->middleware('role:admin');
	Route::view('home', 'app.dashboard')->name('dashboard');
	
	Route::get('upload-surat', 'SuratController@index')->name('upload-surat');
	Route::post('upload-surat/simpan', 'SuratController@store')->name('upload-surat.store');
	// Route::post('upload-surat/simpan', 'SuratController@kirim')->name('upload-surat.store');

	Route::get('kirim-surat', 'SuratController@kirimSurat')->name('kirim-surat');
	Route::get('kirim-surat/data', 'SuratController@dataKirim')->name('kirim-surat.data');
	Route::post('kirim-surat/kirim', 'SuratController@kirim')->name('kirim-surat.kirim');
	Route::put('kirim-surat/update', 'SuratController@update')->name('surat.update');
	// Route::post('kirim-surat/destroy/{id}', 'SuratController@destroy')->name('surat.destroy');

	Route::get('surat-masuk', 'SuratController@indexSuratMasuk')->name('surat-masuk');
	Route::get('surat-masuk/data', 'SuratController@dataSuratMasuk')->name('surat-masuk.data');
	Route::get('enkrip', 'SuratController@enkrip')->name('enkrip');
	Route::get('dekrip', 'SuratController@dekrip')->name('dekrip');
	Route::get('buatkunci', 'SuratController@buatkunci')->name('buatkunci');

	Route::get('config', 'ConfigController@index')->name('config');
	Route::get('config/create', 'ConfigController@create')->name('config.create');
	Route::get('config/edit', 'ConfigController@edit')->name('config.edit');
	Route::get('config/data', 'ConfigController@data')->name('config.data');
	Route::post('config/store', 'ConfigController@store')->name('config.store');
	Route::put('config/update', 'ConfigController@update')->name('config.update');
	Route::post('config/destroy/{id}', 'ConfigController@destroy')->name('config.destroy');

	Route::get('user', 'UserController@index')->name('user');
	Route::get('user/data', 'UserController@data')->name('user.data');
	Route::post('user/store', 'UserController@store')->name('user.store');
	Route::put('user/update', 'UserController@update')->name('user.update');
	Route::post('user/destroy/{id}', 'UserController@destroy')->name('user.destroy');

	Route::get('crypto/generate', 'CryptoController@createKey')->name('crypto.generate');
});
