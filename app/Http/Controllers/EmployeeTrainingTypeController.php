<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeTrainingTypeIndexRequest;
use App\Http\Requests\EmployeeTrainingTypeStoreRequest;
use App\Http\Requests\EmployeeTrainingTypeUpdateRequest;
use App\Models\EmployeeTrainingType;
use App\Services\EmployeeTrainingTypeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeTrainingTypeController extends Controller
{
    protected $employeeTrainingTypeService;

    public function __construct(EmployeeTrainingTypeService $employeeTrainingTypeService)
    {
        $this->employeeTrainingTypeService = $employeeTrainingTypeService;
    }

    public function index(EmployeeTrainingTypeIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $ettQ               = EmployeeTrainingType::query()->filter($validated);
            $ett                = isset($validated['paginate']) && $validated['paginate'] ? $ettQ->paginate($validated['perPage'] ?? 10) : $ettQ->get();
            $transformedItems   = $ett instanceof LengthAwarePaginator ? $ett->getCollection() : $ett;
            $transformedEtt     = $transformedItems->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                ];
            });

            if ($ett instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee training type data', $ett->setCollection($transformedEtt));
            }
            return ApiResponseHelper::success('Employee training type data', $transformedEtt);
        } catch (Exception $e) {
            return ApiResponseHelper::success('Failed to get employee training type data', $e->getMessage());
        }
    }

    public function store(EmployeeTrainingTypeStoreRequest $request)
    {
        try {
            $employeeTrainingType = $this->employeeTrainingTypeService->create($request->validated());
            return ApiResponseHelper::success('Employee training type data has been added successfully', $employeeTrainingType);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee training type data', $e->getMessage());
        }
    }

    public function show($employeeTrainingType)
    {
        try {
            $ett = EmployeeTrainingType::find($employeeTrainingType);
            if (!$ett) {
                throw new Exception('Employee training type data not found');
            }
            return ApiResponseHelper::success("Employee training type's detail", $ett);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get employee training type data", $e->getMessage());
        }
    }

    public function update(EmployeeTrainingTypeUpdateRequest $request, $employeeTrainingType)
    {
        try {
            $et = EmployeeTrainingType::find($employeeTrainingType);
            if (!$et) {
                throw new Exception('Employee training type data not found');
            }
            $this->employeeTrainingTypeService->update($et, $request->validated());
            return ApiResponseHelper::success('Employee training type data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee training type data', $e->getMessage());
        }
    }

    public function destroy($employeeTrainingType)
    {
        try {
            $et = EmployeeTrainingType::find($employeeTrainingType);
            if (!$et) {
                throw new Exception('Employee training type data not found');
            }
            $delete = $et->delete();
            if (!$delete) {
                throw new Exception('Failed to delete employee training type data');
            }
            return ApiResponseHelper::success('Employee training type data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Employee training type data failed to delete', $e->getMessage());
        }
    }
}
