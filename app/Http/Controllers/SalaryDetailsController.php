<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class SalaryDetailsController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function employeeSalary($employee)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee not found', []);
        }
        $data = [
            'salary_type'           => $employee->salaryDetails->salary_type ?? null,
            'monthly_base_salary'   => $employee->salaryDetails->monthly_base_salary ?? 0,
            'daily_base_salary'     => $employee->salaryDetails->daily_base_salary ?? 0,
            'meal_allowance'        => $employee->salaryDetails->meal_allowance ?? 0,
            'bonus'                 => $employee->salaryDetails->bonus ?? 0,
            'allowance'             => $employee->salaryDetails->allowance ?? 0,
            'overtime'              => $employee->salaryDetails->overtime ?? 0,
        ];
        return ApiResponseHelper::success("Employee's salary data", $data);
    }

    public function employeeSalaryHistory($employee)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee not found', []);
        }
        $data = $employee->salaryHistory->map(function ($item) {
            return [
                'id'                    => $item->id,
                'salary_type'           => $item->salary_type           ?? 0,
                'monthly_base_salary'   => $item->monthly_base_salary   ?? 0,
                'daily_base_salary'     => $item->daily_base_salary     ?? 0,
                'meal_allowance'        => $item->meal_allowance        ?? 0,
                'bonus'                 => $item->bonus                 ?? 0,
                'allowance'             => $item->allowance             ?? 0,
                'overtime'              => $item->overtime              ?? 0,
            ];
        });
        return ApiResponseHelper::success("Employee's salary history", $data);
    }
}
