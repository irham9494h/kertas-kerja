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

Route::get('/user-info', 'Auth\LoginController@getUserInfo');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {

    Route::get('manajemen-pengguna', 'ManajemenPenggunaController@index')->name('user.index');
    Route::get('manajemen-pengguna/fetch', 'ManajemenPenggunaController@fetchPengguna')->name('user.fetch');
    Route::get('manajemen-pengguna/json', 'ManajemenPenggunaController@json')->name('user-json');
    Route::post('manajemen-pengguna/store', 'ManajemenPenggunaController@store')->name('user.store');

    Route::get('tahun-rekening', 'TahunRekeningController@index')->name('tahun-rek.index');
    Route::get('tahun-rekening/fetch', 'TahunRekeningController@fetch')->name('tahun-rek.fetch');
    Route::post('tahun-rekening/store', 'TahunRekeningController@store')->name('tahun-rek.store');
    Route::put('tahun-rekening/update/{tahun_rekening}', 'TahunRekeningController@update')->name('tahun-rek.update');
    Route::put('tahun-rekening/activate/{tahun_rekening}', 'TahunRekeningController@activate')->name('tahun-rek.activate');
    Route::get('tahun-rekening/delete/{tahun_rekening}', 'TahunRekeningController@destroy')->name('tahun-rek.destroy');

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

    Route::group(['prefix' => 'rekening'], function () {

        Route::group(['prefix' => 'akun'], function () {

            Route::get('/', 'RekeningController@index')->name('rek.akun.index');
            Route::post('/store', 'RekeningController@storeRekAkun')->name('rek.akun.store');
            Route::get('/show/{id}', 'RekeningController@showRekAkun')->name('rek.akun.show');
            Route::put('/update/{id}', 'RekeningController@updateRekAkun')->name('rek.akun.update');
            Route::get('/delete/{id}', 'RekeningController@deleteRekAkun')->name('rek.akun.delete');

        });

        Route::group(['prefix' => 'kelompok'], function () {

            Route::get('/', 'RekeningController@getKelompok')->name('rek.kelompok.index');
            Route::get('/by-akun/{id}', 'RekeningController@getKelompokByAkun')->name('rek.kelompok.by-akun');
            Route::post('/store', 'RekeningController@storeKelompok')->name('rek.kelompok.store');
            Route::get('/show/{id}', 'RekeningController@showKelompok')->name('rek.kelompok.show');
            Route::put('/update/{id}', 'RekeningController@updateKelompok')->name('rek.kelompok.update');
            Route::get('/delete/{id}', 'RekeningController@deleteKelompok')->name('rek.kelompok.delete');

        });

        Route::group(['prefix' => 'jenis'], function () {

            Route::get('/', 'RekeningController@getJenis')->name('rek.jenis.index');
            Route::get('/by-kelompok/{id}', 'RekeningController@getJenisByKelompok')->name('rek.jenis.by-kelompok');
            Route::post('/store', 'RekeningController@storeJenis')->name('rek.jenis.store');
            Route::get('/show/{id}', 'RekeningController@showJenis')->name('rek.jenis.show');
            Route::put('/update/{id}', 'RekeningController@updateJenis')->name('rek.jenis.update');
            Route::get('/delete/{id}', 'RekeningController@deleteJenis')->name('rek.jenis.delete');

        });

        Route::group(['prefix' => 'objek'], function () {

            Route::get('/', 'RekeningController@getObjek')->name('rek.objek.index');
            Route::get('/by-jenis/{id}', 'RekeningController@getObjekByJenis')->name('org.objek.by-jenis');
            Route::post('/store', 'RekeningController@storeObjek')->name('rek.objek.store');
            Route::get('/show/{id}', 'RekeningController@showObjek')->name('rek.objek.show');
            Route::put('/update/{id}', 'RekeningController@updateObjek')->name('rek.objek.update');
            Route::get('/delete/{id}', 'RekeningController@deleteObjek')->name('rek.objek.delete');

        });

        Route::group(['prefix' => 'rincian-objek'], function () {

            Route::get('/', 'RekeningController@getRincianObjek')->name('rek.rincian-objek.index');
            Route::get('/by-objek/{id}', 'RekeningController@getRincianObjekByObjek')->name('org.rincian-objek.by-objek');
            Route::post('/store', 'RekeningController@storeRincianObjek')->name('rek.rincian-objek.store');
            Route::get('/show/{id}', 'RekeningController@showRincianObjek')->name('rek.rincian-objek.show');
            Route::put('/update/{id}', 'RekeningController@updateRincianObjek')->name('rek.rincian-objek.update');
            Route::get('/delete/{id}', 'RekeningController@deleteRincianObjek')->name('rek.rincian-objek.delete');

        });
    });

    Route::group(['prefix' => 'sb'], function () {
        //rekening
        Route::get('/t/kertas-kerja/rekening-pendapatan', 'KertasKerjaController@rekeningPendapatan');
        Route::get('/t/kertas-kerja/rekening-belanja', 'KertasKerjaController@rekeningBelanja');
        Route::get('/t/kertas-kerja/rekening-pembiayaan/{id?}', 'KertasKerjaController@rekeningPembiayaan');

        Route::get('/t/kertas-kerja/kunci-struktur-murni/{tahun_id?}', 'KertasKerjaController@kunciStrukturMurni');
        Route::get('/t/kertas-kerja/buka-struktur-murni/{tahun_id?}', 'KertasKerjaController@bukaStrukturMurni');

        Route::get('/t', 'KertasKerjaController@tahunSumberDana')->name('sb-tahun');
        Route::get('/t/fetch-tahun', 'KertasKerjaController@fetchTahun')->name('sb-tahun.fetch');
        Route::post('/t/store', 'KertasKerjaController@storeTahun')->name('sb-tahun.store');
        Route::get('/t/delete/{tahun}', 'KertasKerjaController@destroyTahun')->name('sb-tahun.delete');

        Route::get('/t/{tahun}/kertas-kerja/{pembahasan}', 'KertasKerjaController@tanggalKertasKerja')->name('sb-tahun.kertas-kerja');

        Route::get('t/{tahun}/kertas-kerja/{pembahasan}/d/{tanggal}/list', 'KertasKerjaController@fetchKertasKerja')->name('sb-tahun.fetch-kertas-kerja');

        Route::get('t/kertas-kerja/d/{tanggal}/list/json', 'KertasKerjaController@fetchPendapatanJson');
        Route::post('t/kertas-kerja/d/store-pendapatan', 'KertasKerjaController@storePendapatan');
        Route::post('t/kertas-kerja/d/update-nominal', 'KertasKerjaController@updateNominal');

        Route::get('t/kertas-kerja/d/{tanggal}/list/pend/json', 'KertasKerjaController@fetchBelanja');
        Route::post('t/kertas-kerja/d/store-belanja', 'KertasKerjaController@storeBelanja');
        Route::post('t/kertas-kerja/d/update-nominal-belanja', 'KertasKerjaController@updateNominalBelanja');

        Route::get('t/kertas-kerja/d/{tanggal}/list/pembiayaan/json', 'KertasKerjaController@fetchPembiayaan');
        Route::post('t/kertas-kerja/d/store-pembiayaan', 'KertasKerjaController@storePembiayaan');
        Route::post('t/kertas-kerja/d/update-nominal-pembiayaan', 'KertasKerjaController@updateNominalPembiayaan');
    });

    Route::get('sb-thn/{sb_tahun}/kertas-kerja/', 'TahunKertasKerjaController@rencanaAnggaran')->name('kertas-kerja.rencana-anggaran');

    //Tanggal kertas kerja
    Route::post('sb-tgl/store', 'TanggalKertasKerjaController@store')->name('sb-tahun.store');
    Route::get('sb-tgl/delete/{sd_tanggal}', 'TanggalKertasKerjaController@destroy')->name('sb-tahun.delete');

    //KERTAS KERJA
    //pendapatan
    Route::get('kertas-kerja/{tanggal_id}/all', 'TanggalKertasKerjaController@fetchKertasKerja')->name('kertas-kerja.pendapatan.all');

    //LAPORAN
    Route::group(['prefix' => 'report/kertas-kerja'], function () {
        Route::get('/', 'Laporan\LaporanKertasKerjaController@index')->name('lap-kk');
        Route::post('/view', 'Laporan\LaporanKertasKerjaController@laporanKertasKerja')->name('lap-kk.view');

        Route::get('/tgl/{tahun_id}', 'Laporan\LaporanKertasKerjaController@getTanggalByTahun');
        Route::get('/2020', 'Laporan\LaporanKertasKerjaController@laporan2020')->name('lap-kk-2020');

//    Route::get('report/kertas-kerja/2020', 'Laporan\LaporanKertasKerjaController@laporan2020')->name('lap-kk-2020');
    });

    Route::get('rek', 'KertasKerjaController@rek');

    Route::get('import', 'Import\FromExcel@index')->name('import');
    Route::post('import-prose', 'Import\FromExcel@prosesImport')->name('import-prose');
});

