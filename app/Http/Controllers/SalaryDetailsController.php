<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class SalaryDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function employeeSalary($employee)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee not found', [], 404);
        }
        $data = [
            'monthly_base_salary'   => $employee->salaryDetails->monthly_base_salary ?? 0,
            'daily_base_salary'     => $employee->salaryDetails->daily_base_salary ?? 0,
            'meal_allowance'        => $employee->salaryDetails->meal_allowance ?? 0,
            'bonus'                 => $employee->salaryDetails->bonus ?? 0,
            'allowance'             => $employee->salaryDetails->allowance ?? 0,
        ];
        return ApiResponseHelper::success("Employee's salary data", $data);
    }

    public function employeeSalaryHistory($employee)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee not found', [], 404);
        }
        $data = $employee->salaryHistory->map(function ($item) {
            return [
                'id'                    => $item->id,
                'monthly_base_salary'   => $item->monthly_base_salary   ?? 0,
                'daily_base_salary'     => $item->daily_base_salary     ?? 0,
                'meal_allowance'        => $item->meal_allowance        ?? 0,
                'bonus'                 => $item->bonus                 ?? 0,
                'allowance'             => $item->allowance             ?? 0,
            ];
        });
        return ApiResponseHelper::success("Employee's salary history", $data);
    }
}
