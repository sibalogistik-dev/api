<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AttendanceIndexRequest;
use App\Http\Requests\AttendanceReportRequest;
use App\Http\Requests\AttendanceStoreRequest;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Services\AttendanceService;
use App\Services\AttendanceServiceHRD;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class AbsensiController extends Controller
{
    protected $attendanceService;
    protected $attendanceServiceHRD;

    public function __construct(AttendanceService $attendanceService, AttendanceServiceHRD $attendanceServiceHRD)
    {
        $this->attendanceService    = $attendanceService;
        $this->attendanceServiceHRD = $attendanceServiceHRD;
        $this->middleware('permission:hrd.attendance|hrd.attendance.index')->only('index');
        $this->middleware('permission:hrd.attendance|hrd.attendance.show')->only('show');
        $this->middleware('permission:hrd.attendance|hrd.attendance.store')->only('store');
        $this->middleware('permission:hrd.attendance|hrd.attendance.update')->only('update');
        $this->middleware('permission:hrd.attendance|hrd.attendance.destroy')->only('destroy');
        $this->middleware('permission:hrd.attendance|hrd.attendance.report')->only('report');
    }

    public function index(AttendanceIndexRequest $request)
    {
        try {
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
                    'check_in_image'        => $item->check_in_image,
                    'check_out_image'       => $item->check_out_image,
                    'start_time'            => $item->start_time,
                    'end_time'              => $item->end_time,
                ];
            });

            if ($absensi instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Attendance list', $absensi->setCollection($transformedAbsensi));
            }
            return ApiResponseHelper::success('Attendance list', $transformedAbsensi);
        } catch (\Throwable $th) {
            return ApiResponseHelper::error('Failed to get attendance data');
        }
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

    public function report(AttendanceReportRequest $request)
    {
        try {
            $validated = $request->validated();

            $employee_id    = $validated['employee_id'] ?? null;
            $employee       = null;
            $start          = Carbon::parse($validated['start_date'])->startOfDay();
            $end            = Carbon::parse($validated['end_date'])->endOfDay();
            if ($employee_id != null) {
                $employee = Karyawan::find($employee_id);
                if (!$employee) {
                    throw new Exception('Employee data not found.');
                }
            }
            $report = $this->attendanceServiceHRD->generateAttendanceReport($validated);
            $pdf    = Pdf::loadView('attendance.report', compact('report', 'start', 'end', 'employee'))->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan Absensi.pdf');
        } catch (Exception $e) {
            return ApiResponseHelper::error("Error when generating attendance report", $e->getMessage());
        }
    }

    public function attendanceCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => ['sometimes', 'date_format:Y-m'],
        ]);

        $validated = $validator->validated();
        $month     = $validated['month'] ?? now()->format('Y-m');

        $statusMap = [
            1 => 'present',
            2 => 'permission',
            3 => 'sick',
            4 => 'absent',
            5 => 'leave',
            6 => 'offday',
        ];

        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $startDate = $currentMonth->copy()->subMonth()->day(28);
        $endDate   = $currentMonth->copy()->day(27);

        $raw = DB::table('absensis')
            ->selectRaw('date, attendance_status_id, COUNT(*) as total')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date', 'attendance_status_id')
            ->get();

        $indexed = [];

        foreach ($raw as $row) {
            if (!isset($statusMap[$row->attendance_status_id])) {
                continue;
            }

            $dateKey = Carbon::parse($row->date)->toDateString();
            $status  = $statusMap[$row->attendance_status_id];

            $indexed[$dateKey][$status] = (int) $row->total;
        }

        $result = [];
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            if ($cursor->isSunday()) {
                $cursor->addDay();
                continue;
            }

            $date = $cursor->toDateString();

            $row = [
                'date'        => $date,
                'present'     => 0,
                'permission'  => 0,
                'sick'        => 0,
                'absent'      => 0,
                'leave'       => 0,
                'offday'      => 0,
            ];

            if (isset($indexed[$date])) {
                foreach ($indexed[$date] as $key => $value) {
                    $row[$key] = $value;
                }
            }

            $result[] = $row;
            $cursor->addDay();
        }
        return ApiResponseHelper::success('Attendance count', $result);
    }

    public function attendanceUnsubmitted(Request $request)
    {
        $today = $request->input('date') ?? Carbon::today()->toDateString();
        $data = Karyawan::query()
            ->whereDoesntHave('attendance', function ($q) use ($today) {
                $q->where('date', $today);
            })
            ->get();
        $result = $data->map(function ($item) {
            return [
                'id'                => $item->id,
                'name'              => $item->name,
                'branch_name'       => $item->branch->name,
                'job_title_name'    => $item->jobTitle->name,
                'phone'             => $item->employeeDetails->phone_number
            ];
        });
        return ApiResponseHelper::success('Unsubmitted attendance data', $result);
    }
}
