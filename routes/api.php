<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('user', function (Request $request) {
            return $request->user();
        });
    });

Route::get('/login', [AuthController::class, 'login'])->name('login');
