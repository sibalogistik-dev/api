<?php

namespace App\Services;

use App\Models\StatusAbsensi;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendanceStatusService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $attendanceStatus = StatusAbsensi::create($data);
            DB::commit();
            return $attendanceStatus;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save attendance status data: ' . $e->getMessage());
        }
    }

    public function update(StatusAbsensi $attendanceStatus, array $data)
    {
        DB::beginTransaction();
        try {
            $attendanceStatus->update($data);
            DB::commit();
            return $attendanceStatus;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update attendance status data: ' . $e->getMessage());
        }
    }
}
