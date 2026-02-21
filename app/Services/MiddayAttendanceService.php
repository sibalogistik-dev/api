<?php

namespace App\Services;

use App\Models\Karyawan;
use App\Models\MiddayAttendance;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
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
            $today      = Carbon::now()->toDateString();
            $karyawan   = Karyawan::find($data['employee_id']);

            if (!$karyawan) {
                throw new Exception('Employee data not found');
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function update(MiddayAttendance $middayAttendance, array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            // 
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
}
