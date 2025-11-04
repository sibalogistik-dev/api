<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\OvertimeIndexRequest;
use App\Http\Requests\OvertimeStoreRequest;
use App\Http\Requests\OvertimeUpdateRequest;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class OvertimeController extends Controller
{
    protected $overtimeService;

    public function __construct(OvertimeService $overtimeService)
    {
        $this->overtimeService = $overtimeService;
    }

    public function index(OvertimeIndexRequest $request)
    {
        $validated              = $request->validated();
        $otQuery                = Overtime::query()->filter($validated);
        $overtimes              = isset($validated['paginate']) && $validated['paginate'] ? $otQuery->paginate($validated['perPage'] ?? 10) : $otQuery->get();
        $itemsToTransform       = $overtimes instanceof LengthAwarePaginator ? $overtimes->getCollection() : $overtimes;
        $transformedOvertimes   = $itemsToTransform->map(function ($item) {
            return [
                'id'            => $item->id,
                'employee_id'   => $item->employee_id,
                'name'          => $item->employee->name,
                'start_time'    => $item->start_time,
                'end_time'      => $item->end_time,
                'approved'      => $item->approved,
            ];
        });
        if ($overtimes instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Overtime list', $overtimes->setCollection($transformedOvertimes));
        } else {
            return ApiResponseHelper::success('Overtime list', $transformedOvertimes);
        }
    }

    public function store(OvertimeStoreRequest $request)
    {
        try {
            $overtime = $this->overtimeService->create($request->validated());
            return ApiResponseHelper::success('Overtime data has been added successfully', $overtime);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving overtime data', $e->getMessage(), 500);
        }
    }

    public function show($overtime)
    {
        $overtime = Overtime::find($overtime);
        if (!$overtime) {
            return ApiResponseHelper::error('Overtime not found', [], 404);
        }
        $data = [
            'id'                => $overtime->id,
            'employee_id'       => $overtime->employee_id,
            'start_time'        => $overtime->start_time,
            'end_time'          => $overtime->end_time,
            'approved'          => $overtime->approved,
        ];
        return ApiResponseHelper::success("Overtime's detail", $data);
    }

    public function update(OvertimeUpdateRequest $request, $overtime)
    {
        //
    }

    public function destroy($overtime)
    {
        //
    }
}
