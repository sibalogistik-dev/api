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
    Route::get('/province/get', [IndonesiaController::class, 'getProvince'])
        ->name('province.get');
    Route::get('/province/get/{code}', [IndonesiaController::class, 'getProvince'])
        ->name('province.code');
    Route::get('/province/get/{code}/city', [IndonesiaController::class, 'getProvinceCity'])
        ->name('province.city');
    // City
    Route::get('/city/get/{code}', [IndonesiaController::class, 'getCity'])
        ->name('city.code');
    Route::get('/city/get/{code}/district', [IndonesiaController::class, 'getCityDistrict'])
        ->name('city.district');
    // District
    Route::get('/district/get/{code}', [IndonesiaController::class, 'getDistrict'])
        ->name('district.code');
    Route::get('/district/get/{code}/village', [IndonesiaController::class, 'getDistrictVillage'])
        ->name('district.village');
    // Village
    Route::get('/village/get/{code}', [IndonesiaController::class, 'getVillage'])
        ->name('village.code');
});
