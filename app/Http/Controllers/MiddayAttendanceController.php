<?php

namespace App\Http\Controllers;

use App\Http\Requests\MiddayAttendanceIndexRequest;
use App\Http\Requests\MiddayAttendanceStoreRequest;
use App\Http\Requests\MiddayAttendanceUpdateRequest;
use App\Models\MiddayAttendance;
use App\Services\MiddayAttendanceService;
use Illuminate\Http\Request;
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
        //
    }

    public function store(MiddayAttendanceStoreRequest $request)
    {
        //
    }

    public function show(MiddayAttendance $middayAttendance)
    {
        //
    }

    public function update(MiddayAttendanceUpdateRequest $request, MiddayAttendance $middayAttendance)
    {
        //
    }

    public function destroy(MiddayAttendance $middayAttendance)
    {
        //
    }

    public function report($request)
    {
        // 
    }
}
