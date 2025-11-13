<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceIndexRequest;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Services\AttendanceService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(AttendanceIndexRequest $request)
    {
        $validated          = $request->validated();
        $absensiQuery       = Absensi::query()->filter($validated)->orderBy('id', 'desc');
        $absensi            = isset($validated['paginate']) && $validated['paginate'] ? $absensiQuery->paginate($validated['perPage'] ?? 10) : $absensiQuery->get();
        $itemsToTransform   = $absensi instanceof LengthAwarePaginator ? $absensi->getCollection() : $absensi;
        $transformedAbsensi = $itemsToTransform->map(function ($item) {
            return [
                'id'                    => $item->id,
                'employee_id'           => $item->employee_id,
                'employee_name'         => $item->employee->name,
                'branch_name'           => $item->employee->branch->name,
                'attendance_status_id'  => $item->attendance_status_id,
                'status'                => $item->attendanceStatus->name,
                'date'                  => $item->date,
                'checked_in'            => $item->start_time,
                'checked_out'           => $item->end_time,
            ];
        });

        if ($absensi instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Attendance list', $absensi->setCollection($transformedAbsensi));
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

    public function show($attendance)
    {
        $query = Absensi::find($attendance);
        if (!$query) {
            return ApiResponseHelper::error('Attendance not found', [], 404);
        }
        $data = [
            'id'                    => $query->id,
            'name'                  => $query->employee->name,
            'attendance_status_id'  => $query->attendance_status_id,
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

    public function update(AttendanceUpdateRequest $request, $attendance)
    {
        try {
            $abs = Absensi::find($attendance);
            if (!$abs) {
                throw new Exception('Attendance not found');
            }
            $absensi = $this->attendanceService->update($abs, $request->validated());
            return ApiResponseHelper::success("Attendance successfully recorded.", $absensi);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Error when saving attendance data", $e->getMessage(), 500);
        }
    }

    public function destroy($attendance)
    {
        $absensi = Absensi::find($attendance);
        if (!$absensi) {
            return ApiResponseHelper::error('Attendance data not found', [], 404);
        }
        $delete = $absensi->delete();
        if (!$delete) {
            return ApiResponseHelper::error('Attendance data failed to delete', null, 500);
        }
        return ApiResponseHelper::success('Attendance data has been deleted successfully');
    }

    public function employeeAttendance($employee, Request $request)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee not found', [], 404);
        }
        $attendanceQuery = Absensi::query()
            ->where('employee_id', $employee->id)
            ->orderBy('id', 'desc');

        $attendanceQuery->when(
            $request->has('date') && $request->input('date'),
            fn($query)  => $query->where('date', '=', $request->input('date'))
        );
        $attendance = $attendanceQuery->when(
            $request->has('paginate') && $request->input('paginate'),
            fn($query)  => $query->paginate($request->input('perPage', 10)),
            fn($query)  => $query->get()
        );

        $data = $attendance->map(function ($item) {
            return [
                'id'                => $item->id,
                'date'              => $item->date,
                'status'            => $item->attendanceStatus->name,
                'checked_in'        => $item->start_time,
                'checked_out'       => $item->end_time,
                'description'       => $item->description,
                'late'              => $item->late_arrival_time ? true : false,
                'late_arrival_time' => $item->late_arrival_time,
            ];
        });
        return ApiResponseHelper::success("Employee's attendance", $data);
    }
}
