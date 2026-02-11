<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeRollingScheduleIndexRequest;
use App\Http\Requests\EmployeeRollingScheduleStoreRequest;
use App\Http\Requests\EmployeeRollingScheduleUpdateRequest;
use App\Models\EmployeeRollingSchedule;
use App\Services\EmployeeRollingScheduleService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

class EmployeeRollingScheduleController extends Controller
{
    protected $employeeRollingScheduleService;

    public function __construct(EmployeeRollingScheduleService $employeeRollingScheduleService)
    {
        $this->employeeRollingScheduleService = $employeeRollingScheduleService;
        $this->middleware('permission:hrd.employee-rolling-schedule|hrd.employee-rolling-schedule.index', ['only' => ['index']]);
        $this->middleware('permission:hrd.employee-rolling-schedule|hrd.employee-rolling-schedule.show', ['only' => ['show']]);
        $this->middleware('permission:hrd.employee-rolling-schedule|hrd.employee-rolling-schedule.store', ['only' => ['store']]);
        $this->middleware('permission:hrd.employee-rolling-schedule|hrd.employee-rolling-schedule.update', ['only' => ['update']]);
        $this->middleware('permission:hrd.employee-rolling-schedule|hrd.employee-rolling-schedule.destroy', ['only' => ['destroy']]);
    }

    public function index(EmployeeRollingScheduleIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $ersQ               = EmployeeRollingSchedule::query()->filter($validated);
            $ers                = isset($validated['paginate']) && $validated['paginate'] ? $ersQ->paginate($validated['perPage'] ?? 10) : $ersQ->get();
            $transformedItems   = $ers instanceof LengthAwarePaginator ? $ers->getCollection() : $ers;
            $transformedErs     = $transformedItems->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'employee_id'           => $item->employee_id,
                    'employee_name'         => $item->employee?->name,
                    'from_branch_id'        => $item->from_branch_id,
                    'from_branch_company'   => $item->fromBranch?->company?->name,
                    'from_branch_city'      => $item->fromBranch?->village?->district->city?->name,
                    'from_branch_name'      => $item->fromBranch?->name,
                    'to_branch_id'          => $item->to_branch_id,
                    'to_branch_company'     => $item->toBranch?->company?->name,
                    'to_branch_city'        => $item->toBranch?->village?->district->city?->name,
                    'to_branch_name'        => $item->toBranch?->name,
                    'start_date'            => $item->start_date,
                    'end_date'              => $item->end_date,
                ];
            });

            if ($ers instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee rolling schedule data', $ers->setCollection($transformedErs));
            }
            return ApiResponseHelper::success('Employee rolling schedule data', $transformedErs);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee rolling schedule data', $e->getMessage());
        }
    }

    public function store(EmployeeRollingScheduleStoreRequest $request)
    {
        try {
            $employeeRollingSchedule = $this->employeeRollingScheduleService->create($request->validated());
            return ApiResponseHelper::success('Employee rolling schedule data has been added successfully', $employeeRollingSchedule);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to add employee rolling schedule data', $e->getMessage());
        }
    }

    public function show($employeeRollingSchedule)
    {
        try {
            $rollingSchedule = EmployeeRollingSchedule::find($employeeRollingSchedule);
            if (!$rollingSchedule) {
                throw new Exception('Employee rolling schedule data not found');
            }
            $data = [
                'id'                    => $rollingSchedule->id,
                'employee_id'           => $rollingSchedule->employee_id,
                'employee_name'         => $rollingSchedule->employee?->name,
                'from_branch_id'        => $rollingSchedule->from_branch_id,
                'from_branch_company'   => $rollingSchedule->fromBranch?->company?->name,
                'from_branch_city'      => $rollingSchedule->fromBranch?->village?->district->city?->name,
                'from_branch_name'      => $rollingSchedule->fromBranch?->name,
                'to_branch_id'          => $rollingSchedule->to_branch_id,
                'to_branch_company'     => $rollingSchedule->toBranch?->company?->name,
                'to_branch_city'        => $rollingSchedule->toBranch?->village?->district->city?->name,
                'to_branch_name'        => $rollingSchedule->toBranch?->name,
                'start_date'            => $rollingSchedule->start_date,
                'end_date'              => $rollingSchedule->end_date,
            ];
            return ApiResponseHelper::success("Employee rolling schedule detail", $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get employee rolling schedule data", $e->getMessage());
        }
    }

    public function update(EmployeeRollingScheduleUpdateRequest $request, $employeeRollingSchedule)
    {
        try {
            $employeeRollSched = EmployeeRollingSchedule::find($employeeRollingSchedule);
            if (!$employeeRollSched) {
                throw new Exception('Employee rolling schedule data not found');
            }
            $this->employeeRollingScheduleService->update($employeeRollSched, $request->validated());
            return ApiResponseHelper::success('Employee rolling schedule data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to update employee rolling schedule data', $e->getMessage());
        }
    }

    public function destroy($employeeRollingSchedule)
    {
        try {
            $employeeRollSched = EmployeeRollingSchedule::find($employeeRollingSchedule);
            if (!$employeeRollSched) {
                throw new Exception('Employee rolling schedule data not found');
            }
            $employeeRollSched->delete();
            return ApiResponseHelper::success('Employee rolling schedule data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to delete employee rolling schedule data', $e->getMessage());
        }
    }
}
