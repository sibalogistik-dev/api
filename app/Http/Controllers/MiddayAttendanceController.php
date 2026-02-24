<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\MiddayAttendanceIndexRequest;
use App\Http\Requests\MiddayAttendanceStoreRequest;
use App\Http\Requests\MiddayAttendanceUpdateRequest;
use App\Models\MiddayAttendance;
use App\Services\MiddayAttendanceService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

class MiddayAttendanceController extends Controller
{
    public $middayAttendanceService;

    public function __construct(MiddayAttendanceService $middayAttendanceService)
    {
        $this->middayAttendanceService  = $middayAttendanceService;
        $this->middleware('permission:hrd.attendance|hrd.attendance.index')->only('index');
        $this->middleware('permission:hrd.attendance|hrd.attendance.show')->only('show');
        $this->middleware('permission:hrd.attendance|hrd.attendance.store')->only('store');
        $this->middleware('permission:hrd.attendance|hrd.attendance.update')->only('update');
        $this->middleware('permission:hrd.attendance|hrd.attendance.destroy')->only('destroy');
        $this->middleware('permission:hrd.attendance|hrd.attendance.report')->only('report');
    }

    public function index(MiddayAttendanceIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $midAttQ            = MiddayAttendance::query()->filter($validated);
            $midAtt             = isset($validated['paginate']) && $validated['paginate'] ? $midAttQ->paginate($validated['perPage'] ?? 10) : $midAttQ->get();
            $transformedItems   = $midAtt instanceof LengthAwarePaginator ? $midAtt->getCollection() : $midAtt;
            $transformedMidAtt  = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'employee_id'   => $item->employee_id,
                    'employee_name' => $item->employee->name,
                    'branch_name'   => $item->employee->branch->name,
                    'date_time'     => $item->date_time,
                    'image'         => $item->image,
                    'longitude'     => $item->longitude,
                    'latitude'      => $item->latitude,
                ];
            });

            if ($midAtt instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Attendance list', $midAtt->setCollection($transformedMidAtt));
            }
            return ApiResponseHelper::success('Attendance list', $transformedMidAtt);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get midday attendance data', $e->getMessage());
        }
    }

    public function store(MiddayAttendanceStoreRequest $request)
    {
        try {
            $middayAttendance = $this->middayAttendanceService->create($request->validated());
            return ApiResponseHelper::success('Midday attendance created successfully', $middayAttendance);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to create midday attendance', $e->getMessage());
        }
    }

    public function show($middayAttendance)
    {
        try {
            $query = MiddayAttendance::find($middayAttendance);
            if (!$query) {
                return ApiResponseHelper::error('Midday attendance not found', null, 404);
            }
            $data = [
                'id'            => $query->id,
                'employee_id'   => $query->employee_id,
                'employee_name' => $query->employee->name,
                'branch_name'   => $query->employee->branch->name,
                'date_time'     => $query->date_time,
                'image'         => $query->image,
                'longitude'     => $query->longitude,
                'latitude'      => $query->latitude,
                'description'   => $query->description,
            ];
            return ApiResponseHelper::success('Midday attendance retrieved successfully', $query);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to retrieve midday attendance', $e->getMessage());
        }
    }

    public function update(MiddayAttendanceUpdateRequest $request, $middayAttendance)
    {
        try {
            $query  = MiddayAttendance::find($middayAttendance);
            if (!$query) {
                return ApiResponseHelper::error('Midday attendance not found', null, 404);
            }
            $middayAttendance   = $this->middayAttendanceService->update($query, $request->validated());
            return ApiResponseHelper::success('Midday attendance updated successfully', $middayAttendance);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to update midday attendance', $e->getMessage());
        }
    }

    public function destroy($middayAttendance)
    {
        try {
            $query = MiddayAttendance::find($middayAttendance);
            if (!$query) {
                return ApiResponseHelper::error('Midday attendance not found', null, 404);
            }
            $query->delete();
            return ApiResponseHelper::success('Midday attendance deleted successfully', null);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to delete midday attendance', $e->getMessage());
        }
    }

    public function report($request)
    {
        try {
            // 
        } catch (Exception $e) {
            // 
        }
    }
}
