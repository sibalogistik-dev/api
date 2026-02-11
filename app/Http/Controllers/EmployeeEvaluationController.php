<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeEvaluationIndexRequest;
use App\Http\Requests\EmployeeEvaluationStoreRequest;
use App\Http\Requests\EmployeeEvaluationUpdateRequest;
use App\Models\EmployeeEvaluation;
use App\Services\EmployeeEvaluationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeEvaluationController extends Controller
{
    protected $employeeEvaluationService;

    public function __construct(EmployeeEvaluationService $employeeEvaluationService)
    {
        $this->employeeEvaluationService = $employeeEvaluationService;
    }

    public function index(EmployeeEvaluationIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $eeQ                = EmployeeEvaluation::query()->filter($validated);
            $ee                 = isset($validated['paginate']) && $validated['paginate'] ? $eeQ->paginate($validated['perPage'] ?? 10) : $eeQ->get();
            $transformedItems   = $ee instanceof LengthAwarePaginator ? $ee->getCollection() : $ee;
            $transformedEe      = $transformedItems->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'employee_id'           => $item->employee_id,
                    'employee_name'         => $item->employee?->name,
                    'branch_name'           => $item->employee?->branch?->name,
                    'evaluator_id'          => $item->evaluator_id,
                    'evaluator_name'        => $item->evaluator?->name,
                    'evaluation_date'       => $item->evaluation_date,
                    'description'           => $item->description,
                ];
            });
            if ($ee instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee evaluation data', $ee->setCollection($transformedEe));
            }
            return ApiResponseHelper::success('Employee evaluation data', $transformedEe);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee evaluation data', $e->getMessage());
        }
    }

    public function store(EmployeeEvaluationStoreRequest $request)
    {
        try {
            $employeeEvaluation = $this->employeeEvaluationService->create($request->validated());
            return ApiResponseHelper::success('Employee evaluation data has been added successfully', $employeeEvaluation);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to add employee evaluation data', $e->getMessage());
        }
    }

    public function show($employeeEvaluation)
    {
        try {
            $employeeEvaluation = EmployeeEvaluation::find($employeeEvaluation);
            if (!$employeeEvaluation) {
                throw new Exception('Employee evaluation data not found');
            }
            $data = [
                'id'                    => $employeeEvaluation->id,
                'employee_id'           => $employeeEvaluation->employee_id,
                'employee_name'         => $employeeEvaluation->employee?->name,
                'branch_name'           => $employeeEvaluation->employee?->branch?->name,
                'evaluator_id'          => $employeeEvaluation->evaluator_id,
                'evaluator_name'        => $employeeEvaluation->evaluator?->name,
                'evaluation_date'       => $employeeEvaluation->evaluation_date,
                'description'           => $employeeEvaluation->description,
            ];
            return ApiResponseHelper::success('Employee evaluation data', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee evaluation data', $e->getMessage());
        }
    }

    public function update(EmployeeEvaluationUpdateRequest $request, $employeeEvaluation)
    {
        try {
            $employeeEvaluation = EmployeeEvaluation::find($employeeEvaluation);
            if (!$employeeEvaluation) {
                throw new Exception('Employee evaluation data not found');
            }
            $this->employeeEvaluationService->update($employeeEvaluation, $request->validated());
            return ApiResponseHelper::success('Employee evaluation data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to update employee evaluation data', $e->getMessage());
        }
    }

    public function destroy($employeeEvaluation)
    {
        try {
            $employeeEvaluation = EmployeeEvaluation::find($employeeEvaluation);
            if (!$employeeEvaluation) {
                throw new Exception('Employee evaluation data not found');
            }
            $employeeEvaluation->delete();
            return ApiResponseHelper::success('Employee evaluation data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to delete employee evaluation data', $e->getMessage());
        }
    }
}
