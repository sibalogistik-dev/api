<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\OvertimeIndexRequest;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        //
    }

    public function show(Overtime $overtime)
    {
        //
    }

    public function update(Request $request, Overtime $overtime)
    {
        //
    }

    public function destroy(Overtime $overtime)
    {
        //
    }
}
