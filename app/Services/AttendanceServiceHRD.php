<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Karyawan;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AttendanceServiceHRD
{
    public function create(array $data)
    {
        DB::beginTransaction();
        $filePaths = [];
        try {
            $karyawan   = Karyawan::find($data['employee_id']);
            if (!$karyawan) {
                throw new Exception('Employee data not found.');
            }

            $data['check_in_image'] = 'uploads/check_in_image/default.webp';

            if (!empty($data['sick_note'])) {
                $filePaths['sick_note'] = $this->storeFile($data['sick_note'], 'uploads/check_in_image', $karyawan->name);
                $data['sick_note']      = $filePaths['sick_note'];
            } else {
                $data['sick_note']       = null;
            }

            $data['check_in_longitude'] = 0;
            $data['check_in_latitude']  = 0;

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
        $saneName       = Str::slug($employeeName);
        $filename       = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.webp';
        $fullPath       = $path . '/' . $filename;
        $imageContent   = Image::read($file->getRealPath())->toWebp($quality);

        Storage::disk('public')->put($fullPath, (string) $imageContent);

        return $fullPath;
    }
}
