<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndonesiaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\KotaKabController;
use App\Http\Controllers\ProvinsiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use Laravolt\Indonesia\Http\Controllers\ProvinsiController;

Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('user', function (Request $request) {
                return $request->user();
            });
            Route::resource('provinsi', ProvinsiController::class);
            Route::resource('kotakab', KotaKabController::class);
            Route::resource('kecamatan', KecamatanController::class);
            Route::resource('kelurahan', KelurahanController::class);
        });
    // Authentication
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
    //Province
    Route::get('/provinsi/get', [IndonesiaController::class, 'getProvince'])
        ->name('provinsi.get');
    Route::get('/provinsi/get/{code}', [IndonesiaController::class, 'getProvince'])
        ->name('provinsi.code');
    Route::get('/provinsi/get/{code}/kotakab', [IndonesiaController::class, 'getProvinceCity'])
        ->name('provinsi.kotakab');
    // City
    Route::get('/kotakab/get/{code}', [IndonesiaController::class, 'getCity'])
        ->name('kotakab.code');
    Route::get('/kotakab/get/{code}/kecamatan', [IndonesiaController::class, 'getCityDistrict'])
        ->name('kotakab.kecamatan');
    // District
    Route::get('/kecamatan/get/{code}', [IndonesiaController::class, 'getDistrict'])
        ->name('kecamatan.code');
    Route::get('/kecamatan/get/{code}/kelurahan', [IndonesiaController::class, 'getDistrictVillage'])
        ->name('kecamatan.kelurahan');
    // Village
    Route::get('/kelurahan/get/{code}', [IndonesiaController::class, 'getVillage'])
        ->name('kelurahan.code');
});
