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
use Illuminate\Support\Facades\DB;

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
            $transformedItems       = $karyawan instanceof LengthAwarePaginator ? $karyawan->getCollection() : $karyawan;
            $transformedKaryawan    = $transformedItems->map(function ($item) {
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
        DB::beginTransaction();
        try {
            $employee = Karyawan::find($employee);
            if (!$employee) {
                throw new Exception('Employee data not found');
            }
            $employeeDelete = $employee->user->delete();
            $delete = $employee->delete();
            if (!$employeeDelete) {
                throw new Exception('Failed to delete user data');
                DB::rollBack();
            }
            if (!$delete) {
                throw new Exception('Failed to delete employee data');
                DB::rollBack();
            }
            DB::commit();
            return ApiResponseHelper::success('Employee data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting employee data', $e->getMessage());
        }
    }

    public function restore($employee)
    {
        DB::beginTransaction();
        try {
            $employee = Karyawan::where('id', $employee)
                ->onlyTrashed()
                ->first();
            if (!$employee) {
                throw new Exception('Employee data not found');
            }
            $userRestore = $employee->user->restore();
            $restore = $employee->restore();
            if (!$userRestore) {
                throw new Exception('Failed to restore user data');
                DB::rollBack();
            }
            if (!$restore) {
                throw new Exception('Failed to restore employee data');
                DB::rollBack();
            }
            DB::commit();
            return ApiResponseHelper::success('Employee data has been restored successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when restoring employee data', $e->getMessage());
        }
    }
}
