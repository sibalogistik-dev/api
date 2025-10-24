<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceIndexRequest;
use App\Http\Requests\AttendanceStoreRequest;
use App\Models\Absensi;
use App\Services\AttendanceService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(AttendanceIndexRequest $request)
    {
        $validated      = $request->validated();
        $user           = Auth::user();
        $absensiQuery   = Absensi::query()->filter($validated);
        if (!($validated['getAll'] ?? false)) {
            $employee   = $user->employee;
            if ($employee) {
                $absensiQuery->where('employee_id', $employee->id);
            }
        }
        $absensiQuery->orderBy('id', 'desc');
        $absensi            = isset($validated['paginate']) && $validated['paginate'] ? $absensiQuery->paginate($validated['perPage'] ?? 10) : $absensiQuery->get();
        $itemsToTransform   = $absensi instanceof LengthAwarePaginator ? $absensi->getCollection() : $absensi;
        $transformedAbsensi = $itemsToTransform->map(function ($item) {
            return [
                'id'                    => $item->id,
                'name'                  => $item->employee->name,
                'status'                => $item->attendanceStatus->name,
                'date'                  => $item->date,
                'checked_in'            => $item->start_time,
                'checked_out'           => $item->end_time,
            ];
        });

        if ($absensi instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Attendance list', $absensi, $transformedAbsensi);
        }

        return ApiResponseHelper::success('Attendance list', $transformedAbsensi);
    }

    public function store(AttendanceStoreRequest $request)
    {
        try {
            $attendance = $this->attendanceService->create($request->validated());
            return ApiResponseHelper::success("Attendance successfully recorded.", $attendance);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Error when saving attendance data", $e->getMessage(), 500);
        }
    }

    public function show($absensi)
    {
        $query = Absensi::withTrashed()->find($absensi);
        $data = [
            'id'                    => $query->id,
            'name'                  => $query->employee->name,
            'status'                => $query->attendanceStatus->name,
            'description'           => $query->description,
            'date'                  => $query->date,
            'checked_in'            => $query->start_time,
            'checked_out'           => $query->end_time,
            'checked_in_latitude'   => $query->latitude,
            'checked_in_longitude'  => $query->longitude,
            'attendance_image'      => $query->attendance_image,
            'late_arrival_time'     => $query->late_arrival_time,
        ];
        return ApiResponseHelper::success('Attendance detail', $data);
    }

    public function edit(Absensi $absensi)
    {
        //
    }

    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    public function destroy(Absensi $absensi)
    {
        //
    }
}
