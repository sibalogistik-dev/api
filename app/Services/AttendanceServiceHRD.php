<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\StatusAbsensi;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class attendanceServiceHRD
{
    public function create(array $data)
    {
        DB::beginTransaction();
        $filePaths = [];
        try {
            $karyawan           = Karyawan::find($data['employee_id']);
            $attendance_status  = StatusAbsensi::find($data['attendance_status_id']);
            $today              = $data['date'] ?? Carbon::now()->toDateString();

            if (!$karyawan) {
                throw new Exception('Employee data not found.');
            }

            if (!$attendance_status) {
                throw new Exception('Attendance status data not found.');
            }

            $data['date']           = $today;
            $data['check_in_image'] = 'uploads/check_in_image/default.webp';

            if ($attendance_status->name == 'Hadir' || $attendance_status->name == 'hadir') {
                $data['start_time']         = $data['start_time'] ?? date('H:i:s');
                $data['check_in_longitude'] = $karyawan->branch->longitude;
                $data['check_in_latitude']  = $karyawan->branch->latitude;
                $data['late_arrival_time']  = $this->countLate($data['start_time'], $karyawan->branch->start_time);
            } else {
                $data['start_time']         = $data['start_time'] ?? date('H:i:s');
                $data['late_arrival_time']  = 0;
                if (!empty($data['sick_note'])) {
                    $filePaths['sick_note'] = $this->storeFile($data['sick_note'], 'uploads/sick_note', $karyawan->name);
                    $data['sick_note']      = $filePaths['sick_note'];
                } else {
                    $data['sick_note']      = null;
                }
            }

            $attendance = Absensi::create($data);

            DB::commit();
            return $attendance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save attendance data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path, string $employeeName, int $quality = 90)
    {
        $saneName = Str::slug($employeeName);
        $isImage = Str::startsWith($file->getMimeType(), 'image/');

        if ($isImage) {
            $filename = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.webp';
            $fullPath = $path . '/' . $filename;

            $imageContent = Image::read($file->getRealPath())->toWebp($quality);
            Storage::disk('public')->put($fullPath, (string) $imageContent);

            return $fullPath;
        } else {
            $extension = $file->getClientOriginalExtension();
            $filename  = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.' . $extension;
            $fullPath  = $path . '/' . $filename;

            Storage::disk('public')->putFileAs($path, $file, $filename);

            return $fullPath;
        }
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
