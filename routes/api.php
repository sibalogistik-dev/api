<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AgamaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\IndonesiaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\KotaKabController;
use App\Http\Controllers\MarriageStatusController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProvinsiController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login', [AuthController::class, 'loginError'])->name('login');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('user', [AuthController::class, 'user']);

            /* resource routes */
            // master data
            Route::resource('agama', AgamaController::class)
                ->except(['create', 'edit']);
            Route::resource('perusahaan', PerusahaanController::class)
                ->except(['create', 'edit']);
            Route::resource('pendidikan', PendidikanController::class)
                ->except(['create', 'edit']);
            Route::resource('status-nikah', MarriageStatusController::class)
                ->except(['create', 'edit']);

            Route::resource('absensi', AbsensiController::class)
                ->except(['create', 'edit']);
            Route::resource('cabang', CabangController::class)
                ->except(['create', 'edit']);
            Route::resource('jabatan', JabatanController::class)
                ->except(['create', 'edit']);
            Route::resource('karyawan', KaryawanController::class)
                ->except(['edit']);
            Route::resource('kecamatan', KecamatanController::class)
                ->except(['create', 'edit']);
            Route::resource('kelurahan', KelurahanController::class)
                ->except(['create', 'edit']);
            Route::resource('kotakab', KotaKabController::class)
                ->except(['create', 'edit']);
            Route::resource('provinsi', ProvinsiController::class)
                ->except(['create', 'edit']);
        });

    Route::get('/province/get', [IndonesiaController::class, 'getProvince']);
    Route::get('/province/get/{code}', [IndonesiaController::class, 'getProvince']);
    Route::get('/province/get/{code}/city', [IndonesiaController::class, 'getProvinceCity']);
    Route::get('/city/get/{code}', [IndonesiaController::class, 'getCity']);
    Route::get('/city/get/{code}/district', [IndonesiaController::class, 'getCityDistrict']);
    Route::get('/district/get/{code}', [IndonesiaController::class, 'getDistrict']);
    Route::get('/district/get/{code}/village', [IndonesiaController::class, 'getDistrictVillage']);
    Route::get('/village/get/{code}', [IndonesiaController::class, 'getVillage']);
});
