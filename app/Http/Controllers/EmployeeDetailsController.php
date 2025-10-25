<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class EmployeeDetailsController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
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
    public function edit(string $id)
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

    public function employeeDetails($employee)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee not found', [], 404);
        }
        $data = [
            'gender'                => $employee->employeeDetails->gender ?? null,
            'religion'              => $employee->employeeDetails->religion_id ?? null,
            'phone_number'          => $employee->employeeDetails->phone_number ?? null,
            'place_of_birth_id'     => $employee->employeeDetails->place_of_birth_id ?? null,
            'date_of_birth'         => $employee->employeeDetails->date_of_birth ?? null,
            'address'               => $employee->employeeDetails->address ?? null,
            'blood_type'            => $employee->employeeDetails->blood_type ?? null,
            'education_id'          => $employee->employeeDetails->education_id ?? null,
            'marriage_status_id'    => $employee->employeeDetails->marriage_status_id ?? null,
            'residential_area'      => $employee->employeeDetails->residential_area_id ?? null,
            'passport_photo'        => $employee->employeeDetails->passport_photo ?? null,
            'id_card_photo'         => $employee->employeeDetails->id_card_photo ?? null,
            'drivers_license_photo' => $employee->employeeDetails->drivers_license_photo ?? null,
        ];
        return ApiResponseHelper::success("Employee's details", $data);
    }
}
