<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\RemoteAttendanceIndexRequest;
use App\Http\Requests\RemoteAttendanceStoreRequest;
use App\Http\Requests\RemoteAttendanceUpdateRequest;
use App\Models\RemoteAttendance;
use App\Services\RemoteAttendanceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RemoteAttendanceController extends Controller
{
    protected $remoteAttendanceService;

    public function __construct(RemoteAttendanceService $remoteAttendanceService)
    {
        $this->remoteAttendanceService = $remoteAttendanceService;
    }

    public function index(RemoteAttendanceIndexRequest $request)
    {
        try {
            $validated                      = $request->validated();
            $remoteAttendanceQ          = RemoteAttendance::query()->filter($validated);
            $remoteAttendance               = isset($validated['paginate']) && $validated['paginate'] ? $remoteAttendanceQ->paginate($validated['perPage'] ?? 10) : $remoteAttendanceQ->get();
            $transformedItems               = $remoteAttendance instanceof LengthAwarePaginator ? $remoteAttendance->getCollection() : $remoteAttendance;
            $transformedRemoteAttendance    = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'employee_id'   => $item->employee_id,
                    'employee_name' => $item->employee->name,
                    'start_date'    => $item->start_date,
                    'end_date'      => $item->end_date,
                ];
            });
            if ($remoteAttendance instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Remote Attendance list', $remoteAttendance->setCollection($transformedRemoteAttendance));
            } else {
                return ApiResponseHelper::success('Remote Attendance list', $transformedRemoteAttendance);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::success('Failed to get remote attendance data', $e->getMessage());
        }
    }

    public function store(RemoteAttendanceStoreRequest $request)
    {
        try {
            $remoteAttendance = $this->remoteAttendanceService->create($request->validated());
            return ApiResponseHelper::success('Remote Attendance data has been added successfully', $remoteAttendance);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving remote attendance data', $e->getMessage());
        }
    }

    public function show($remoteAttendance)
    {
        try {
            $remoteAttendance = RemoteAttendance::find($remoteAttendance);
            if (!$remoteAttendance) {
                throw new Exception('Remote attendance data not found');
            }
            return ApiResponseHelper::success('Remote attendance data', $remoteAttendance);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get remote attendance data', $e->getMessage());
        }
    }

    public function update(RemoteAttendanceUpdateRequest $request, $remoteAttendance)
    {
        try {
            $remoteAttendance = RemoteAttendance::find($remoteAttendance);
            if (!$remoteAttendance) {
                throw new Exception('Remote Attendance data not found');
            }
            $this->remoteAttendanceService->update($remoteAttendance, $request->validated());
            return ApiResponseHelper::success('Remote Attendance data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating remote attendance data', $e->getMessage());
        }
    }

    public function destroy($remoteAttendance)
    {
        try {
            $remoteAttendance = RemoteAttendance::find($remoteAttendance);
            if (!$remoteAttendance) {
                throw new Exception('Remote attendance data not found');
            }

            $delete = $remoteAttendance->delete();
            if (!$delete) {
                throw new Exception('Failed to delete remote attendance data', 500);
            }
            return ApiResponseHelper::success('Remote attendance data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Remote attendance data failed to delete', $e->getMessage());
        }
    }
}
