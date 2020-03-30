<?php

use Illuminate\Support\Facades\Auth;

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
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {

    Route::get('manajemen-pengguna/json', 'ManajemenPenggunaController@json')->name('user-json');
    Route::resource('manajemen-pengguna', 'ManajemenPenggunaController');

    Route::group(['prefix' => 'organisasi'], function () {

        Route::group(['prefix' => 'urusan'], function () {

            Route::get('/', 'OrganisasiController@index')->name('org.urusan.index');
            Route::post('/store', 'OrganisasiController@storeUrusan')->name('org.urusan.store');
            Route::get('/show/{id}', 'OrganisasiController@showUrusan')->name('org.urusan.show');
            Route::put('/update/{id}', 'OrganisasiController@updateUrusan')->name('org.urusan.update');
            Route::get('/delete/{id}', 'OrganisasiController@deleteUrusan')->name('org.urusan.delete');

        });

        Route::group(['prefix' => 'bidang'], function () {

            Route::get('/', 'OrganisasiController@getBidang')->name('org.bidang.index');
            Route::get('/by-urusan/{id}', 'OrganisasiController@getBidangByUrusan')->name('org.bidang.by-urusan');
            Route::post('/store', 'OrganisasiController@storeBidang')->name('org.bidang.store');
            Route::get('/show/{id}', 'OrganisasiController@showBidang')->name('org.bidang.show');
            Route::put('/update/{id}', 'OrganisasiController@updateBidang')->name('org.bidang.update');
            Route::get('/delete/{id}', 'OrganisasiController@deleteBidang')->name('org.bidang.delete');

        });

        Route::group(['prefix' => 'unit'], function () {

            Route::get('/', 'OrganisasiController@getUnit')->name('org.unit.index');
            Route::get('/by-bidang/{id}', 'OrganisasiController@getUnitByBidang')->name('org.unit.by-bidang');
            Route::post('/store', 'OrganisasiController@storeUnit')->name('org.unit.store');
            Route::get('/show/{id}', 'OrganisasiController@showUnit')->name('org.unit.show');
            Route::put('/update/{id}', 'OrganisasiController@updateUnit')->name('org.unit.update');
            Route::get('/delete/{id}', 'OrganisasiController@deleteUnit')->name('org.unit.delete');

        });

        Route::group(['prefix' => 'subunit'], function () {

            Route::get('/', 'OrganisasiController@getSubUnit')->name('org.subunit.index');
            Route::get('/by-unit/{id}', 'OrganisasiController@getSubUnitByUnit')->name('org.subunit.by-unit');
            Route::post('/store', 'OrganisasiController@storeSubUnit')->name('org.subunit.store');
            Route::get('/show/{id}', 'OrganisasiController@showSubUnit')->name('org.subunit.show');
            Route::put('/update/{id}', 'OrganisasiController@updateSubUnit')->name('org.subunit.update');
            Route::get('/delete/{id}', 'OrganisasiController@deleteSubUnit')->name('org.subunit.delete');

        });

    });
});
