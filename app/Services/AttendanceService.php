<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\StatusAbsensi;
use App\Models\RemoteAttendance;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AttendanceService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $today      = $data['date'] ?? Carbon::now()->toDateString();
            $karyawan   = Karyawan::find($data['employee_id']);

            if (!$karyawan) {
                throw new Exception('Employee data not found.');
            }

            $attendance = Absensi::where('employee_id', $karyawan->id)
                ->where('date', $today)
                ->orderBy('start_time', 'desc')
                ->first();

            if (!$attendance) {
                return $this->handleClockIn($data, $karyawan, $today);
            } elseif (is_null($attendance->end_time)) {
                return $this->handleClockOut($data, $karyawan, $attendance, $today);
            } else {
                throw new Exception('Attendance for today is already complete (clock-in and clock-out recorded).');
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save attendance data: ' . $e->getMessage());
        }
    }

    private function handleClockIn(array $data, Karyawan $karyawan, string $today)
    {
        $filePaths = [];
        try {
            $attendance_status  = StatusAbsensi::find($data['attendance_status_id']);
            $kantor             = $karyawan->branch;
            $attendanceData     = [
                'employee_id'           => $data['employee_id'],
                'attendance_status_id'  => $data['attendance_status_id'],
                'description'           => $data['description'],
                'check_in_longitude'    => $data['check_in_longitude'],
                'check_in_latitude'     => $data['check_in_latitude'],
            ];

            $existingAttendance = Absensi::where('employee_id', $karyawan->id)
                ->where('date', $today)
                ->exists();

            if ($existingAttendance) {
                throw new Exception('An attendance record already exists for today. Please clock out first.');
            }

            if ($attendance_status->name == 'Hadir' || $attendance_status->name == 'hadir') {
                $isRemoteActive = RemoteAttendance::where('employee_id', $karyawan->id)
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today)
                    ->exists();

                if (!$isRemoteActive) {
                    $jarak  = $this->distanceCount($kantor->latitude, $kantor->longitude, $data['check_in_latitude'], $data['check_in_longitude']);
                    $rad    = $kantor->attendance_radius;

                    if ($jarak > $rad) {
                        throw new Exception('Distance is too far from office location. Your distance is ' . number_format($jarak, 0, ',', '.') . ' meters.');
                    }
                }

                if (!empty($data['check_in_image'])) {
                    $filePaths['check_in_image']        = $this->storeFile($data['check_in_image'], 'uploads/check_in_image', $karyawan->name);
                }
                $attendanceData['date']                 = $today;
                $attendanceData['start_time']           = $data['start_time'] ?? date('H:i:s');
                $attendanceData['check_in_image']       = $filePaths['check_in_image'];
                $late                                   = $this->countLate($attendanceData['start_time'], $kantor->start_time);
                $attendanceData['late_arrival_time']    = $late;
                $attendanceData['sick_note']            = null;
            } else {
                $attendanceData['date']                 = $today;
                $attendanceData['start_time']           = '00:00:00';
                $attendanceData['check_in_image']       = 'uploads/check_in_image/default.webp';
                if (!empty($data['sick_note'])) {
                    $filePaths['sick_note']             = $this->storeFile($data['sick_note'], 'uploads/sick_note', $karyawan->name);
                }
            }

            $attendance = Absensi::create($attendanceData);
            DB::commit();
            return $attendance;
        } catch (Exception $e) {
            foreach ($filePaths as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            throw $e;
        }
    }

    private function handleClockOut(array $data, Karyawan $karyawan, Absensi $attendance, string $today)
    {
        $filePaths = [];
        try {
            $kantor = $karyawan->branch;

            $isRemoteActive = RemoteAttendance::where('employee_id', $karyawan->id)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->exists();

            if (!$isRemoteActive) {
                $jarak = $this->distanceCount($kantor->latitude, $kantor->longitude, $data['check_out_latitude'], $data['check_out_longitude']);
                $rad = $kantor->attendance_radius;

                if ($jarak > $rad) {
                    throw new Exception('Clock-out distance is too far from office. Your distance is ' . number_format($jarak, 0, ',', '.') . ' meters.');
                }
            }

            if (!empty($data['check_out_image'])) {
                $filePaths['check_out_image']   = $this->storeFile($data['check_out_image'], 'uploads/check_out_image', $karyawan->name);
                $attendance->check_out_image    = $filePaths['check_out_image'];
            } else {
                $attendance->check_out_image    = 'uploads/check_out_image/default.webp';
            }
            $attendance->check_out_longitude    = $data['check_out_longitude'];
            $attendance->check_out_latitude     = $data['check_out_latitude'];
            $attendance->end_time               = $data['end_time'] ?? date('H:i:s');
            $attendance->save();

            DB::commit();
            return $attendance;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Absensi $absensi, array $data)
    {
        DB::beginTransaction();
        try {
            $update = [
                'attendance_status_id'  => $data['attendance_status_id'],
                'description'           => $data['description'],
                'start_time'            => $data['start_time']
            ];
            if (isset($data['end_time'])) {
                $update['end_time'] = $data['end_time'];
            }
            $late   = $this->countLate($update['start_time'], $absensi->employee->branch->start_time);
            $update['late_arrival_time']    = $late;

            $absensi->update($update);
            DB::commit();
            return $absensi;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update attendance data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path, string $employeeName, int $quality = 90)
    {
        $saneName       = Str::slug($employeeName);
        $filename       = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.webp';
        $fullPath       = $path . '/' . $filename;
        $imageContent   = Image::read($file->getRealPath())->toWebp($quality);

        Storage::disk('public')->put($fullPath, (string) $imageContent);

        return $fullPath;
    }

    private function distanceCount($lat_kantor, $long_kantor, $lat_pengguna, $long_pengguna)
    {
        $earthRadius    = 6371000;
        $lat1Rad        = deg2rad($lat_kantor);
        $long1Rad       = deg2rad($long_kantor);
        $lat2Rad        = deg2rad($lat_pengguna);
        $long2Rad       = deg2rad($long_pengguna);
        $deltaLat       = $lat2Rad - $lat1Rad;
        $deltaLong      = $long2Rad - $long1Rad;
        $hitung_radian  = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLong / 2) * sin($deltaLong / 2);
        $hitung_radian  = 2 * atan2(sqrt($hitung_radian), sqrt(1 - $hitung_radian));
        $distance       = round($earthRadius * $hitung_radian, 0);
        return $distance;
    }

    private function countLate($actual_time, $required_start_time)
    {
        try {
            $actual     = Carbon::parse($actual_time);
            $required   = Carbon::parse($required_start_time);
            if ($actual->isAfter($required)) {
                return abs((int) $actual->diffInMinutes($required));
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}
