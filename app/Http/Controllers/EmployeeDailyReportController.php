<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeDailyReportIndexRequest;
use App\Http\Requests\EmployeeDailyReportStoreRequest;
use App\Http\Requests\EmployeeDailyReportUpdateRequest;
use App\Models\EmployeeDailyReport;
use App\Services\EmployeeDailyReportService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeDailyReportController extends Controller
{
    protected $employeeDailyReportService;

    public function __construct(EmployeeDailyReportService $employeeDailyReportService)
    {
        $this->employeeDailyReportService   = $employeeDailyReportService;
    }

    public function index(EmployeeDailyReportIndexRequest $request)
    {
        try {
            $validated                      = $request->validated();
            $jdQ                            = EmployeeDailyReport::query()->filter($validated);
            $employeeDailyReport            = isset($validated['paginate']) && $validated['paginate'] ? $jdQ->paginate($validated['perPage'] ?? 10) : $jdQ->get();
            $transformedItems               = $employeeDailyReport instanceof LengthAwarePaginator ? $employeeDailyReport->getCollection() : $employeeDailyReport;
            $transformedEmployeeDailyReport = $transformedItems->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'employee_id'           => $item->employee_id,
                    'employee_name'         => $item->employee->name,
                    'date'                  => $item->date,
                    'job_title'             => $item->employee->jobTitle->name,
                    'job_description_id'    => $item->job_description_id,
                    'job_description'       => $item->jobDescription->task_name,
                    'description'           => $item->description,
                ];
            });
            if ($employeeDailyReport instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee\'s daily report data', $employeeDailyReport->setCollection($transformedEmployeeDailyReport));
            }
            return ApiResponseHelper::success('Employee\'s daily report data', $transformedEmployeeDailyReport);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee\'s daily report ', $e->getMessage());
        }
    }

    public function store(EmployeeDailyReportStoreRequest $request)
    {
        try {
            $validated      = $request->validated();
            $dailyReport    = $this->employeeDailyReportService->create($validated);
            return ApiResponseHelper::success('Employee\'s daily report data has been added successfully', $dailyReport);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee\'s daily report data', $e->getMessage());
        }
    }

    public function show($dailyReport)
    {
        try {
            $dailyReport    = EmployeeDailyReport::find($dailyReport);
            if (!$dailyReport) {
                throw new Exception('Employee\'s daily report data not found');
            }
            return ApiResponseHelper::success('Employee\'s daily report detail', $dailyReport);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee\'s daily report', $e->getMessage());
        }
    }


    public function update(EmployeeDailyReportUpdateRequest $request, $dailyReport)
    {
        try {
            $dailyReport = EmployeeDailyReport::find($dailyReport);
            if (!$dailyReport) {
                throw new Exception('Employee\'s daily report data not found');
            }
            $this->employeeDailyReportService->update($dailyReport, $request->validated());
            return ApiResponseHelper::success('Employee\'s daily report data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee\'s daily report data', $e->getMessage());
        }
    }

    public function destroy($dailyReport)
    {
        try {
            $dailyReport = EmployeeDailyReport::find($dailyReport);
            if (!$dailyReport) {
                throw new Exception('Employee\'s daily report data not found');
            }
            $delete = $dailyReport->delete();
            if (!$delete) {
                throw new Exception('Employee\'s daily report data failed to delete');
            }
            return ApiResponseHelper::success('Employee\'s daily report data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting employee\'s daily report data', $e->getMessage());
        }
    }
}
