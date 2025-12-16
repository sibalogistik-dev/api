<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/login',   [App\Http\Controllers\AuthController::class, 'login']);
    Route::get('/login',    [App\Http\Controllers\AuthController::class, 'loginError'])->name('login');

    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('user', [App\Http\Controllers\AuthController::class, 'user']);

            Route::apiResources([
                // master data
                'religion'          => App\Http\Controllers\AgamaController::class,
                'education'         => App\Http\Controllers\PendidikanController::class,
                'marriage-status'   => App\Http\Controllers\MarriageStatusController::class,
                'attendance-status' => App\Http\Controllers\StatusAbsensiController::class,

                // Indonesia Regional
                'village'           => App\Http\Controllers\KelurahanController::class,
                'district'          => App\Http\Controllers\KecamatanController::class,
                'city'              => App\Http\Controllers\KotaKabController::class,
                'province'          => App\Http\Controllers\ProvinsiController::class,

                // sensitive data
                'company'           => App\Http\Controllers\PerusahaanController::class,
                'branch'            => App\Http\Controllers\CabangController::class,
                'employee'          => App\Http\Controllers\KaryawanController::class,
                'job-title'         => App\Http\Controllers\JabatanController::class,
                'job-description'   => App\Http\Controllers\JobDescriptionController::class,
                'attendance'        => App\Http\Controllers\AbsensiController::class,
                'remote-attendance' => App\Http\Controllers\RemoteAttendanceController::class,
                'daily-report'      => App\Http\Controllers\EmployeeDailyReportController::class,
                'overtime'          => App\Http\Controllers\OvertimeController::class,
                'payroll'           => App\Http\Controllers\PayrollController::class,
                'resign'            => App\Http\Controllers\ResignController::class,
                'face-recognition'  => App\Http\Controllers\FaceRecognitionModelController::class,
                'branch-asset'      => App\Http\Controllers\BranchAssetController::class,
                'reprimand-letter'  => App\Http\Controllers\ReprimandLetterController::class,
                'warning-letter'    => App\Http\Controllers\WarningLetterController::class,
            ]);
            // documents route
            Route::post('attendance/report',                    [App\Http\Controllers\AbsensiController::class,         'report'])->name('attendance.report');
            Route::post('warning-letter/report',                [App\Http\Controllers\WarningLetterController::class,   'report'])->name('warning-letter.report');
            Route::post('warning-letter/document',              [App\Http\Controllers\WarningLetterController::class,   'document'])->name('warning-letter.report');
            Route::post('payroll/report',                       [App\Http\Controllers\PayrollController::class,         'report'])->name('payroll.report');
            Route::post('payroll/slip',                         [App\Http\Controllers\PayrollController::class,         'slip'])->name('payroll.slip');

            // restore route
            Route::post('employee/{employee}/restore',          [App\Http\Controllers\KaryawanController::class,        'restore'])->name('employee.restore');

            // support route
            Route::get('employee/{employee}/details',           [App\Http\Controllers\EmployeeDetailsController::class, 'employeeDetails'])->name('employee.details');
            Route::get('employee/{employee}/salary',            [App\Http\Controllers\SalaryDetailsController::class,   'employeeSalary'])->name('employee.salary');
            Route::get('employee/{employee}/salary-histories',  [App\Http\Controllers\SalaryDetailsController::class,   'employeeSalaryHistory'])->name('employee.salaryHistory');
            Route::get('employee/{employee}/attendances',       [App\Http\Controllers\AbsensiController::class,         'employeeAttendance'])->name('employee.attendances');
            Route::get('company/{company}/branches',            [App\Http\Controllers\PerusahaanController::class,      'companyBranches'])->name('company.branches');

            Route::post('attendance/store-by-hrd',              [App\Http\Controllers\AbsensiController::class,         'hrdAttendanceAdd'])->name('attendance.storeByHRD');
            Route::post('payroll/{employee}/generate',          [App\Http\Controllers\PayrollController::class,         'generatePayrollPersonal'])->name('payroll.generatePersonal');
        });

    Route::get('storage-file',                  [App\Http\Controllers\StorageController::class, 'getStorageFile'])->name('storage.file');
    // getter public routes
    Route::get('/get/province',                 [App\Http\Controllers\IndonesiaController::class, 'getAllProvince']);
    Route::get('/get/province/{code}',          [App\Http\Controllers\IndonesiaController::class, 'getProvince']);
    Route::get('/get/province/{code}/city',     [App\Http\Controllers\IndonesiaController::class, 'getProvinceCity']);

    Route::get('/get/city',                     [App\Http\Controllers\IndonesiaController::class, 'getAllCity']);
    Route::get('/get/city/{code}',              [App\Http\Controllers\IndonesiaController::class, 'getCity']);
    Route::get('/get/city/{code}/district',     [App\Http\Controllers\IndonesiaController::class, 'getCityDistrict']);

    Route::get('/get/district',                 [App\Http\Controllers\IndonesiaController::class, 'getAllDistrict']);
    Route::get('/get/district/{code}',          [App\Http\Controllers\IndonesiaController::class, 'getDistrict']);
    Route::get('/get/district/{code}/village',  [App\Http\Controllers\IndonesiaController::class, 'getDistrictVillage']);

    Route::get('/get/village',                  [App\Http\Controllers\IndonesiaController::class, 'getAllVillage']);
    Route::get('/get/village/{code}',           [App\Http\Controllers\IndonesiaController::class, 'getVillage']);
});
