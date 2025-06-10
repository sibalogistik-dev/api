<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndonesiaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\KotaKabController;
use App\Http\Controllers\ProvinsiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('user', function (Request $request) {
                return $request->user();
            });
            Route::resource('provinsi', ProvinsiController::class)
                ->except(['create', 'edit']);
            Route::resource('kotakab', KotaKabController::class)
                ->except(['create', 'edit']);
            Route::resource('kecamatan', KecamatanController::class)
                ->except(['create', 'edit']);
            Route::resource('kelurahan', KelurahanController::class)
                ->except(['create', 'edit']);
        });
    // Authentication
    Route::get('/login', [AuthController::class, 'loginError'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    //Province
    Route::get('/province/get', [IndonesiaController::class, 'getProvince']);
    Route::get('/province/get/{code}', [IndonesiaController::class, 'getProvince']);
    Route::get('/province/get/{code}/city', [IndonesiaController::class, 'getProvinceCity']);
    // City
    Route::get('/city/get/{code}', [IndonesiaController::class, 'getCity']);
    Route::get('/city/get/{code}/district', [IndonesiaController::class, 'getCityDistrict']);
    // District
    Route::get('/district/get/{code}', [IndonesiaController::class, 'getDistrict']);
    Route::get('/district/get/{code}/village', [IndonesiaController::class, 'getDistrictVillage']);
    // Village
    Route::get('/village/get/{code}', [IndonesiaController::class, 'getVillage']);
});
