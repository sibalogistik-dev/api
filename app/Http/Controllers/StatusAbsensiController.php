<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceStatusIndexRequest;
use App\Models\StatusAbsensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusAbsensiController extends Controller
{
    protected $attendanceStatusService;

    public function __construct($attendanceStatusService)
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
            //code...
        }
    }

    public function store(Request $request)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function show(StatusAbsensi $attendanceStatus)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function update(Request $request, StatusAbsensi $attendanceStatus)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function destroy(StatusAbsensi $attendanceStatus)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }
}
