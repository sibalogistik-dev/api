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

    private function storeFile(UploadedFile $file, string $path, int $quality = 90)
    {
        $isImage = Str::startsWith($file->getMimeType(), 'image/');
        $filename = now()->format('Ymd-His') . '-' . Str::random(10);
        $fullPath = $path . '/' . $filename;

        if ($isImage) {
            $fullPath .= '.png';

            $image = Image::read($file->getRealPath())->toPng();

            Storage::disk('public')->put($fullPath, (string) $image);

            return $fullPath;
        }

        $extension = $file->getClientOriginalExtension();
        $fullPath .= '.' . $extension;

        Storage::disk('public')->putFileAs($path, $file, basename($fullPath));

        return $fullPath;
    }
}
