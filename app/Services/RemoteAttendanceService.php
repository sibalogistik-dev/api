<?php

namespace App\Services;

use App\Models\RemoteAttendance;
use App\Models\Village;
use Exception;
use Illuminate\Support\Facades\DB;

class RemoteAttendanceService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $remoteAttendance = RemoteAttendance::create($data);
            DB::commit();
            return $remoteAttendance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save remote attendance data: ' . $e->getMessage());
        }
    }

    public function update(RemoteAttendance $remoteAttendance, array $data)
    {
        DB::beginTransaction();
        try {
            $remoteAttendance->update($data);
            DB::commit();
            return $remoteAttendance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update remote attendance data: ' . $e->getMessage());
        }
    }
}
