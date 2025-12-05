<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceIndexRequest;
use App\Http\Requests\AttendancePrintRequest;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Services\AttendanceService;
use App\Services\AttendanceServiceHRD;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    protected $attendanceService;
    protected $attendanceServiceHRD;

    public function __construct(AttendanceService $attendanceService, AttendanceServiceHRD $attendanceServiceHRD)
    {
        $this->attendanceService    = $attendanceService;
        $this->attendanceServiceHRD = $attendanceServiceHRD;
    }

    public function index(AttendanceIndexRequest $request)
    {
        $validated          = $request->validated();
        $absensiQ           = Absensi::query()->filter($validated)->orderBy('date', 'desc');
        $absensi            = isset($validated['paginate']) && $validated['paginate'] ? $absensiQ->paginate($validated['perPage'] ?? 10) : $absensiQ->get();
        $transformedItems   = $absensi instanceof LengthAwarePaginator ? $absensi->getCollection() : $absensi;
        $transformedAbsensi = $transformedItems->map(function ($item) {
            return [
                'id'                    => $item->id,
                'employee_id'           => $item->employee_id,
                'employee_name'         => $item->employee->name,
                'branch_name'           => $item->employee->branch->name,
                'attendance_status_id'  => $item->attendance_status_id,
                'status'                => $item->attendanceStatus->name,
                'date'                  => $item->date,
                'start_time'            => $item->start_time,
                'end_time'              => $item->end_time,
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
            return ApiResponseHelper::error("Error when saving attendance data", $e->getMessage());
        }
    }

    public function show($attendance)
    {
        try {
            $query = Absensi::find($attendance);
            if (!$query) {
                throw new Exception('Attendance data not found');
            }
            $data = [
                'id'                    => $query->id,
                'employee_id'           => $query->employee_id,
                'attendance_status_id'  => $query->attendance_status_id,
                'status'                => $query->attendanceStatus->name,
                'description'           => $query->description,
                'date'                  => $query->date,
                'start_time'            => $query->start_time,
                'end_time'              => $query->end_time,
                'check_in_latitude'     => $query->check_in_latitude,
                'check_in_longitude'    => $query->check_in_longitude,
                'check_out_latitude'    => $query->check_out_latitude,
                'check_out_longitude'   => $query->check_out_longitude,
                'check_in_image'        => $query->check_in_image,
                'check_out_image'       => $query->check_out_image,
                'half_day'              => $query->half_day,
                'sick_note'             => $query->sick_note,
                'late_arrival_time'     => $query->late_arrival_time,
            ];
            return ApiResponseHelper::success('Attendance detail', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to retrieve attendance detail', $e->getMessage());
        }
    }

    public function update(AttendanceUpdateRequest $request, $attendance)
    {
        try {
            $abs        = Absensi::find($attendance);
            if (!$abs) {
                throw new Exception('Attendance data not found');
            }
            $absensi    = $this->attendanceService->update($abs, $request->validated());
            return ApiResponseHelper::success("Attendance successfully recorded.", $absensi);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Error when saving attendance data", $e->getMessage());
        }
    }

    public function destroy($attendance)
    {
        try {
            $absensi = Absensi::find($attendance);
            if (!$absensi) {
                throw new Exception('Attendance data not found');
            }
            $delete = $absensi->delete();
            if (!$delete) {
                throw new Exception('Failed to delete attendance data', 500);
            }
            return ApiResponseHelper::success('Attendance data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Attendance data failed to delete', $e->getMessage());
        }
    }

    public function employeeAttendance($employee, Request $request)
    {
        $employee = Karyawan::find($employee);
        if (!$employee) {
            return ApiResponseHelper::error('Employee data not found', []);
        }
        $attendanceQ = Absensi::query()
            ->where('employee_id', $employee->id)
            ->orderBy('id', 'desc');

        $attendanceQ->when(
            $request->has('date') && $request->input('date'),
            fn($query)  => $query->where('date', '=', $request->input('date'))
        );
        $attendance = $attendanceQ->when(
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

    public function hrdAttendanceAdd(AttendanceStoreRequest $request)
    {
        try {
            $attendance = $this->attendanceServiceHRD->create($request->validated());
            return ApiResponseHelper::success("Attendance successfully recorded.", $attendance);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Error when saving attendance data", $e->getMessage());
        }
    }

    public function printAttendance(AttendancePrintRequest $request)
    {
        try {
            $validated = $request->validated();

            $report = $this->attendanceServiceHRD->generateAttendanceReport($validated);

            return ApiResponseHelper::success("Attendance report generated successfully.", $report);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Error when generating attendance report", $e->getMessage());
        }
    }
}
