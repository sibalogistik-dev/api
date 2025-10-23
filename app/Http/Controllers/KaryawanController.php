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
        $validated              = $request->validated();
        $karyawanQuery          = Karyawan::query()->filter($validated)->orderBy('id', 'desc');
        $karyawan               = isset($validated['paginate']) && $validated['paginate'] ? $karyawanQuery->paginate($validated['perPage'] ?? 10) : $karyawanQuery->get();
        $itemsToTransform       = $karyawan instanceof LengthAwarePaginator ? $karyawan->getCollection() : $karyawan;
        $transformedKaryawan    = $itemsToTransform->map(function ($item) {
            return [
                'id'                => $item->id,
                'name'              => $item->name,
                'npk'               => $item->npk,
                'job_title'         => $item->jobTitle->name ?? null,
                'branch'            => $item->branch->name ?? null,
                'passport_photo'    => $item->employeeDetails->passport_photo ?? null,
            ];
        });
        if ($karyawan instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Employees list', $karyawan->setCollection($transformedKaryawan));
        } else {
            return ApiResponseHelper::success('Employees list', $transformedKaryawan);
        }
    }

    public function create()
    {
        //
    }

    public function store(EmployeeStoreRequest $request)
    {
        try {
            $karyawan = $this->employeeService->create($request->validated());
            return ApiResponseHelper::success('Employee data has been added successfully', $karyawan);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee data', $e->getMessage(), 500);
        }
    }

    public function show($karyawan)
    {
        $data = Karyawan::withTrashed()->find($karyawan);
        if ($data) {
            return ApiResponseHelper::success("Employee's Detail", $data);
        } else {
            return ApiResponseHelper::success("Employee's Detail", []);
        }
    }

    public function update(EmployeeUpdateRequest $request, Karyawan $karyawan)
    {
        try {
            $this->employeeService->update($karyawan, $request->validated());
            return ApiResponseHelper::success('Employee data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee data', $e->getMessage(), 500);
        }
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->user->delete();
        $delete = $karyawan->delete();
        if ($delete) {
            return ApiResponseHelper::success('Employee data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Employee data failed to delete', null, 500);
        }
    }
}
