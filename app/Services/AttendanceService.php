<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\StatusAbsensi;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    public function createIn(array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            $attendanceData = [
                'employee_id'           => $data['employee_id'],
                'attendance_status_id'  => $data['attendance_status_id'],
                'description'           => $data['description'],
                'longitude'             => $data['longitude'],
                'latitude'              => $data['latitude'],
            ];
            $attendance_status = StatusAbsensi::find($data['attendance_status_id']);
            if ($attendance_status->name == 'Hadir' || $attendance_status->name == 'hadir') {
                $filePaths['attendance_image'] = $this->storeFile($data['attendance_image'], 'uploads/attendance_image');
                $attendanceData['date'] = date('Y-m-d');
                $attendanceData['start_time'] = date('H:i:s');
                $attendanceData['attendance_image'] = $filePaths['attendance_image'];
            } else {
                $attendanceData['date'] = date('Y-m-d');
                $attendanceData['start_time'] = '00:00:00';
                $attendanceData['attendance_image'] = 'uploads/attendance_image/default.webp';
            }
            $attendance = Absensi::create($attendanceData);
            DB::commit();
            return $attendance;
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($filePaths as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            throw new Exception('Failed to save branch data: ' . $e->getMessage());
        }
    }

    public function createOut(array $data)
    {
        // 
    }

    public function update(Absensi $absensi, array $data)
    {
        // 
    }

    private function storeFile(UploadedFile $file, string $path)
    {
        return $file->store($path, 'public');
    }
}
