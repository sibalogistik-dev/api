<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceStatusIndexRequest;
use App\Http\Requests\AttendanceStatusStoreRequest;
use App\Http\Requests\AttendanceStatusUpdateRequest;
use App\Models\StatusAbsensi;
use App\Services\AttendanceStatusService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusAbsensiController extends Controller
{
    protected $attendanceStatusService;

    public function __construct(AttendanceStatusService $attendanceStatusService)
    {
        $this->attendanceStatusService = $attendanceStatusService;
    }

    public function index(AttendanceStatusIndexRequest $request)
    {
        try {
            $validated                      = $request->validated();
            $attendanceStatusQ              = StatusAbsensi::query()->filter($validated);
            $attendanceStatus               = isset($validated['paginate']) && $validated('paginate') ? $attendanceStatusQ->paginate($validated['perPage'] ?? 10) : $attendanceStatusQ->get();
            $transformedItems               = $attendanceStatus instanceof LengthAwarePaginator ? $attendanceStatus->getCollection() : $attendanceStatus;
            $transformedAttendanceStatus    = $transformedItems->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                ];
            });
            if ($attendanceStatus instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Attendance status data', $attendanceStatus->setCollection($transformedAttendanceStatus));
            }
            return ApiResponseHelper::success('Attendance status data', $transformedAttendanceStatus);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get attendance status data', $e->getMessage());
        }
    }

    public function store(AttendanceStatusStoreRequest $request)
    {
        try {
            $attendanceStatus   = $this->attendanceStatusService->create($request->validated());
            return ApiResponseHelper::success('Attendance status data has been added successfully', $attendanceStatus);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving attendance status data', $e->getMessage());
        }
    }

    public function show($attendanceStatus)
    {
        try {
            $attendanceStatus   = StatusAbsensi::find($attendanceStatus);
            if (!$attendanceStatus) {
                throw new Exception('Attendance status data not found');
            }
            return ApiResponseHelper::success('Attendance status data', $attendanceStatus);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get attendance status data', $e->getMessage());
        }
    }

    public function update(AttendanceStatusUpdateRequest $request, $attendanceStatus)
    {
        try {
            $attendanceStatus   = StatusAbsensi::find($attendanceStatus);
            if (!$attendanceStatus) {
                throw new Exception('Attendance status data not found');
            }
            $this->attendanceStatusService->update($attendanceStatus, $request->validated());
            return ApiResponseHelper::success('Attendance status data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating attendance status data', $e->getMessage());
        }
    }

    public function destroy($attendanceStatus)
    {
        try {
            $attendanceStatus   = StatusAbsensi::find($attendanceStatus);
            if (!$attendanceStatus) {
                throw new Exception('Attendance status data not found');
            }
            $delete = $attendanceStatus->delete();
            if (!$delete) {
                throw new Exception('Failed to delete attendance status data');
            }
            return ApiResponseHelper::success('Attendance status data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting attendance status data', $e->getMessage());
        }
    }
}
