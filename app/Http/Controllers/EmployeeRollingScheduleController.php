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
        //
    }

    public function show($employeeRollingSchedule)
    {
        //
    }

    public function update(EmployeeRollingScheduleUpdateRequest $request, $employeeRollingSchedule)
    {
        //
    }

    public function destroy($employeeRollingSchedule)
    {
        //
    }
}
