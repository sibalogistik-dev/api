<?php

namespace App\Services;

use App\Models\Karyawan;
use App\Models\MiddayAttendance;
use App\Models\RemoteAttendance;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MiddayAttendanceService
{
    public function create(array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            $today      = Carbon::now();
            $dateTime   = $today->toDateTimeString('second');
            $date       = $today->toDateString();

            $karyawan   = Karyawan::find($data['employee_id']);
            $kantor     = $karyawan->branch;

            $lat = $data['latitude'] ?? 0.00000000;
            $long = $data['longitude'] ?? 0.00000000;

            if (!$karyawan) {
                throw new Exception('Employee data not found. A');
            }

            $isExist = MiddayAttendance::where('employee_id', $karyawan->id)
                ->whereDate('date_time', $date)
                ->exists();

            if ($isExist) {
                throw new Exception('Employee has already checked in for midday attendance today. B');
            }

            $isRemoteActive = RemoteAttendance::where('employee_id', $karyawan->id)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->exists();

            if (!$isRemoteActive) {
                $jarak  = $this->distanceCount($kantor->latitude, $kantor->longitude, $lat, $long);
                $rad    = $kantor->attendance_radius;

                if ($jarak > $rad) {
                    throw new Exception('Distance is too far from office location. Your distance is ' . number_format($jarak, 0, ',', '.') . ' meters. C');
                }
            }
            $data['date_time']          = $dateTime;
            $data['late_arrival_time']  = $this->countLate($data['date_time']);

            $middayAttendanceData = [
                'employee_id'       => $data['employee_id'],
                'date_time'         => $data['date_time'],
                'longitude'         => $data['longitude'] ?? 0.00000000,
                'latitude'          => $data['latitude'] ?? 0.00000000,
                'description'       => $data['description'] ?? null,
                'late_arrival_time' => $data['late_arrival_time']
            ];

            if (!empty($data['image'])) {
                $filePaths['image'] = $this->storeBase64Image(
                    $data['image'],
                    'uploads/midday_attendance',
                    $karyawan->name
                );
            } else {
                $filePaths['image'] = 'uploads/midday_attendance/default.webp';
            }
            $middayAttendanceData['image'] = $filePaths['image'];

            $middayAttendance = MiddayAttendance::create($middayAttendanceData);
            DB::commit();
            return $middayAttendance;
        } catch (Exception $e) {
            foreach ($filePaths as $path) {
                if ($path && $path !== 'uploads/midday_attendance/default.webp') {
                    Storage::disk('public')->delete($path);
                }
            }
            DB::rollBack();
            throw new Exception('Failed to save midday attendance data: ' . $e->getMessage());
        }
    }

    public function update(MiddayAttendance $middayAttendance, array $data)
    {
        DB::beginTransaction();
        $filePaths = [];
        try {
            $karyawan = Karyawan::find($middayAttendance->employee_id);

            if (isset($data['date_time']) && $middayAttendance->date_time != $data['date_time']) {
                $late                       = $this->countLate($data['date_time'], $karyawan->branch->start_time);
                $data['late_arrival_time']  = $late;
            }
        } catch (Exception $e) {
            // 
        }
    }

    private function storeBase64Image(string $base64, string $path, string $employeeName, int $quality = 90)
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64)) {
            throw new \Exception("Invalid base64 image format");
        }

        $saneName = Str::slug($employeeName);

        $data = substr($base64, strpos($base64, ',') + 1);
        $binary = base64_decode($data);

        if ($binary === false) {
            throw new \Exception("Failed to decode base64 image");
        }

        $filename = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.webp';
        $fullPath = $path . '/' . $filename;

        $image = Image::read($binary)->toWebp($quality);

        Storage::disk('public')->put($fullPath, (string) $image);

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

    private function countLate($actual_time, $required_time = '13:00')
    {
        try {
            $actual     = Carbon::parse($actual_time);
            $required   = Carbon::parse($required_time);
            if ($actual->isAfter($required)) {
                return abs((int) $actual->diffInMinutes($required));
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}
