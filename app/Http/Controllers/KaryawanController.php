<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeIndexRequest;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Karyawan;
use App\Services\EmployeeService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class KaryawanController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(EmployeeIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $karyawanQ          = Karyawan::query()->filter($validated)->orderBy('id', 'desc');
            $karyawan               = isset($validated['paginate']) && $validated['paginate'] ? $karyawanQ->paginate($validated['perPage'] ?? 10) : $karyawanQ->get();
            $itemsToTransform       = $karyawan instanceof LengthAwarePaginator ? $karyawan->getCollection() : $karyawan;
            $transformedKaryawan    = $itemsToTransform->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'npk'               => $item->npk,
                    'job_title_id'      => $item->job_title_id,
                    'branch_id'         => $item->branch_id,
                    'passport_photo'    => $item->employeeDetails->passport_photo ?? null,
                ];
            });
            if ($karyawan instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employees data', $karyawan->setCollection($transformedKaryawan));
            } else {
                return ApiResponseHelper::success('Employees data', $transformedKaryawan);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employees data', $e->getMessage());
        }
    }

    public function store(EmployeeStoreRequest $request)
    {
        try {
            $karyawan = $this->employeeService->create($request->validated());
            return ApiResponseHelper::success('Employee data has been added successfully', $karyawan);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee data', $e->getMessage());
        }
    }

    public function show($employee)
    {
        try {
            $employee = Karyawan::find($employee);
            if (!$employee) {
                throw new Exception('Employee data not found');
            }
            return ApiResponseHelper::success("Employee data", $employee);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get employee data", $e->getMessage());
        }
    }

    public function update(EmployeeUpdateRequest $request, $employee)
    {
        try {
            $employee = Karyawan::find($employee);
            if (!$employee) {
                throw new Exception('Employee data not found');
            }
            $this->employeeService->update($employee, $request->validated());
            return ApiResponseHelper::success('Employee data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee data', $e->getMessage());
        }
    }

    public function destroy($employee)
    {
        try {
            $employee = Karyawan::find($employee);
            if (!$employee) {
                throw new Exception('Employee data not found');
            }
            $employee->user->delete();
            $delete = $employee->delete();
            if (!$delete) {
                throw new Exception('Failed to delete employee data');
            }
            return ApiResponseHelper::success('Employee data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting employee data', $e->getMessage());
        }
    }
}
