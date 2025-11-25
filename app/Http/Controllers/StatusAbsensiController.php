<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceStatusIndexRequest;
use App\Models\StatusAbsensi;
use Exception;
use Illuminate\Http\Request;

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
            $validated          = $request->validated();
            $attendanceStatusQ  = StatusAbsensi::query()->filter($validated);
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
