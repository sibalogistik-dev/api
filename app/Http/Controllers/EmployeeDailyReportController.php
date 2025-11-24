<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeDailyReportIndexRequest;
use App\Http\Requests\EmployeeDailyReportStoreRequest;
use App\Http\Requests\EmployeeDailyReportUpdateRequest;
use App\Models\EmployeeDailyReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeDailyReportController extends Controller
{
    protected $employeeDailyReportService;

    public function __construct(EmployeeDailyReport $employeeDailyReportService)
    {
        $this->employeeDailyReportService   = $employeeDailyReportService;
    }

    public function index(EmployeeDailyReportIndexRequest $request)
    {
        try {
            $validated                      = $request->validated();
            $jdQ                            = EmployeeDailyReport::query()->filter($validated);
            $employeeDailyReport            = isset($validated['paginate']) && $validated['paginate'] ? $jdQ->paginate($validated['perPage'] ?? 10) : $jdQ->get();
            $itemsToTransform               = $employeeDailyReport instanceof LengthAwarePaginator ? $employeeDailyReport->getCollection() : $employeeDailyReport;
            $transformedEmployeeDailyReport = $itemsToTransform->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'job_title'         => $item->jobTitle->name,
                    'task_name'         => $item->task_name,
                    'task_detail'       => $item->task_detail,
                    'priority_level'    => $item->priority_level,
                ];
            });
            if ($employeeDailyReport instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee daily report data', $employeeDailyReport->setCollection($transformedEmployeeDailyReport));
            }
            return ApiResponseHelper::success('Employee daily report data', $transformedEmployeeDailyReport);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee daily report ', $e->getMessage());
        }
    }

    public function store(EmployeeDailyReportStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $employeeDailyReport    = $this->employeeDailyReportService->create($validated);
            return ApiResponseHelper::success('Employee daily report data has been added successfully', $employeeDailyReport);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee daily report data', $e->getMessage());
        }
    }

    public function show($dailyReport)
    {
        try {
            $dailyReport = EmployeeDailyReport::find($dailyReport);
            if (!$dailyReport) {
                throw new Exception('Employee daily report data not found');
            }
            return ApiResponseHelper::success('Employee daily report detail', $dailyReport);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee daily report', $e->getMessage());
        }
    }


    public function update(EmployeeDailyReportUpdateRequest $request, $dailyReport)
    {
        try {
            $dailyReport = EmployeeDailyReport::find($dailyReport);
            if (!$dailyReport) {
                throw new Exception('Employee daily report data not found');
            }
            $this->employeeDailyReportService->update($dailyReport, $request->validated());
            return ApiResponseHelper::success('Employee daily report data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee daily report data', $e->getMessage());
        }
    }

    public function destroy($dailyReport)
    {
        try {
            $dailyReport = EmployeeDailyReport::find($dailyReport);
            if (!$dailyReport) {
                throw new Exception('Employee daily report data not found');
            }
            $delete = $dailyReport->delete();
            if (!$delete) {
                throw new Exception('Employee daily report data failed to delete');
            }
            return ApiResponseHelper::success('Employee daily report data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting employee daily report data', $e->getMessage());
        }
    }
}
