<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\IndonesiaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\KotaKabController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProvinsiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')
        ->group(function () {
            // User Profile
            Route::get('user', [AuthController::class, 'user']);
            // Manages CRUD operations for provinces
            Route::resource('provinsi', ProvinsiController::class)
                ->except(['create', 'edit']);
            // Manages CRUD operations for cities/regencies
            Route::resource('kotakab', KotaKabController::class)
                ->except(['create', 'edit']);
            // Manages CRUD operations for districts
            Route::resource('kecamatan', KecamatanController::class)
                ->except(['create', 'edit']);
            // Manages CRUD operations for villages/urban communities
            Route::resource('kelurahan', KelurahanController::class)
                ->except(['create', 'edit']);
            // Manages CRUD operations for companies
            Route::resource('perusahaan', PerusahaanController::class)
                ->except(['create', 'edit']);
            // Manages CRUD operations for company branches
            Route::resource('cabang', CabangController::class)
                ->except(['create', 'edit']);
        });
    // Login
    Route::post('/login', [AuthController::class, 'login']);
    // Direct to unauthenticated user
    Route::get('/login', [AuthController::class, 'loginError'])->name('login');
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
    // Companies & Branches
    Route::get('/business/all', [PerusahaanController::class, 'index']);
    Route::get('/business/{codename}/cabang', [PerusahaanController::class, 'cabangByCodename']);
});
