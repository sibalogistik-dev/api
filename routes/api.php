<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AgamaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\EmployeeDetailsController;
use App\Http\Controllers\IndonesiaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\KotaKabController;
use App\Http\Controllers\MarriageStatusController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\SalaryDetailsController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/login',    [AuthController::class, 'loginError'])->name('login');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('user', [AuthController::class, 'user']);

            Route::apiResources([
                // master data
                'religion'          => AgamaController::class,
                'education'         => PendidikanController::class,
                'marriage_status'   => MarriageStatusController::class,
                'job-title'         => JabatanController::class,

                // Indonesia Regional
                'village'           => KelurahanController::class,
                'district'          => KecamatanController::class,
                'city'              => KotaKabController::class,
                'province'          => ProvinsiController::class,

                // sensitive data
                'company'           => PerusahaanController::class,
                'attendance'        => AbsensiController::class,
                'branch'            => CabangController::class,
                'employee'          => KaryawanController::class,
                'payroll'           => PayrollController::class,
            ]);

            Route::get('employee/{employee}/details',           [EmployeeDetailsController::class,  'employeeDetails']);
            Route::get('employee/{employee}/salary',            [SalaryDetailsController::class,    'employeeSalary']);
            Route::get('employee/{employee}/salary-histories',  [SalaryDetailsController::class,    'employeeSalaryHistory']);
            Route::get('employee/{employee}/attendances',       [AbsensiController::class,          'employeeAttendance']);
            Route::get('company/{company}/branches',            [PerusahaanController::class,       'companyBranches']);
        });

    Route::get('/province/get',                 [IndonesiaController::class, 'getProvince']);
    Route::get('/province/get/{code}',          [IndonesiaController::class, 'getProvince']);
    Route::get('/province/get/{code}/city',     [IndonesiaController::class, 'getProvinceCity']);
    Route::get('/city/get/{code}',              [IndonesiaController::class, 'getCity']);
    Route::get('/city/get/{code}/district',     [IndonesiaController::class, 'getCityDistrict']);
    Route::get('/district/get/{code}',          [IndonesiaController::class, 'getDistrict']);
    Route::get('/district/get/{code}/village',  [IndonesiaController::class, 'getDistrictVillage']);
    Route::get('/village/get/{code}',           [IndonesiaController::class, 'getVillage']);
});
