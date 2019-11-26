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

Route::post('/sapa', function (Request $request) {
    return "ada apa ya ".$request->nama_opd;
});

Route::post('ambil-surat', 'SuratController@ambilSurat')->name('ambil-surat');

