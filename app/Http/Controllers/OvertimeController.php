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
        try {
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
        } catch (Exception $e) {
            return ApiResponseHelper::success('Failed to get overtime data', $e->getMessage());
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
        try {
            $ot = Overtime::find($overtime);
            if (!$ot) {
                throw new Exception('Overtime not found');
            }
            $data = [
                'id'            => $ot->id,
                'employee_id'   => $ot->employee_id,
                'name'          => $ot->employee->name,
                'start_time'    => $ot->start_time,
                'end_time'      => $ot->end_time,
                'approved'      => $ot->approved,
            ];
            return ApiResponseHelper::success("Overtime's detail", $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get overtime data', $e->getMessage(), $e->getCode());
        }
    }

    public function update(OvertimeUpdateRequest $request, Overtime $overtime)
    {
        try {
            $this->overtimeService->update($overtime, $request->validated());
            return ApiResponseHelper::success('Overtime data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating overtime data', $e->getMessage(), 500);
        }
    }

    public function destroy($overtime)
    {
        $overtime = Overtime::find($overtime);
        if (!$overtime) {
            return ApiResponseHelper::error('Overtime data not found', []);
        }
        $delete = $overtime->delete();
        if ($delete) {
            return ApiResponseHelper::success('Overtime data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Overtime data failed to delete', [], 500);
        }
    }
}
