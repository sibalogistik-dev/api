<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndonesiaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('user', function (Request $request) {
                return $request->user();
            });
        });
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::get('/province', [IndonesiaController::class, 'getProvince'])
        ->name('province.get');
    Route::get('/province/{code}', [IndonesiaController::class, 'getProvince'])
        ->name('province.code');
    Route::get('/province/{code}/city', [IndonesiaController::class, 'getProvinceCity'])
        ->name('province.city');

    Route::get('/city/{code}', [IndonesiaController::class, 'getCity'])
        ->name('city.code');

    Route::get('/district/{code}', [IndonesiaController::class, 'getDistrict'])
        ->name('district.code');

    Route::get('/village/{code}', [IndonesiaController::class, 'getVillage'])
        ->name('village.code');
});
