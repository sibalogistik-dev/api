<?php

namespace App\Http\Controllers;

use App\Models\MiddayAttendance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MiddayAttendanceController extends Controller
{
    public function __construct(
        // AttendanceService $attendanceService, 
        // AttendanceServiceHRD $attendanceServiceHRD
    )
    {
        // $this->attendanceService    = $attendanceService;
        // $this->attendanceServiceHRD = $attendanceServiceHRD;
        $this->middleware('permission:hrd.attendance|hrd.attendance.index')->only('index');
        $this->middleware('permission:hrd.attendance|hrd.attendance.show')->only('show');
        $this->middleware('permission:hrd.attendance|hrd.attendance.store')->only('store');
        $this->middleware('permission:hrd.attendance|hrd.attendance.update')->only('update');
        $this->middleware('permission:hrd.attendance|hrd.attendance.destroy')->only('destroy');
        $this->middleware('permission:hrd.attendance|hrd.attendance.report')->only('report');
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(MiddayAttendance $middayAttendance)
    {
        //
    }

    public function update(Request $request, MiddayAttendance $middayAttendance)
    {
        //
    }

    public function destroy(MiddayAttendance $middayAttendance)
    {
        //
    }
}
