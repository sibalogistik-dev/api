<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceIndexRequest;
use App\Http\Requests\AttendanceStoreRequest;
use App\Models\Absensi;
use App\Models\StatusAbsensi;
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
                'description'           => $item->description,
                'date'                  => $item->date,
                'checked_in'            => $item->start_time,
                'checked_out'           => $item->end_time,
                'checked_in_latitude'   => $item->latitude,
                'checked_in_longitude'  => $item->longitude,
                'attendance_image'      => $item->attendance_image,
            ];
        });
        if ($absensi instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success("Attendance's list", $absensi->setCollection($transformedAbsensi));
        } else {
            return ApiResponseHelper::success("Attendance's list", $transformedAbsensi);
        }
    }

    public function create()
    {
        //
    }

    public function store(AttendanceStoreRequest $request)
    {
        try {
            if ($request->input('attendance_type') === 'masuk') {
                $attendance = $this->attendanceService->createIn($request->validated());
            } else if ($request->input('attendance_type') === 'pulang') {
                $attendance = $this->attendanceService->createOut($request->validated());
            }
            return ApiResponseHelper::success("Attendance's list", $attendance);
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
