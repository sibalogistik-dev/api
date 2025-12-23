<?php

namespace App\Services;

use App\Models\StatusAbsensi;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendanceStatusService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return StatusAbsensi::create($data);
        });
    }

    public function update(StatusAbsensi $attendanceStatus, array $data)
    {
        return DB::transaction(function () use ($attendanceStatus, $data) {
            return $attendanceStatus->update($data);
        });
    }
}
