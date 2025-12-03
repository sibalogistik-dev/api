<?php

namespace App\Services;

use App\Models\FaceRecognitionModel;
use App\Models\Karyawan;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FaceRecognitionService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            if (!empty($data['image_path'])) {
                $employee                   = Karyawan::find($data['employee_id']);
                $filePaths['image_path']    = $this->storeBase64Image($data['image_path'], 'uploads/face_model', $employee->name);
                $data['image_path']         = $filePaths['image_path'];
            } else {
                throw new Exception('Image path is required');
            }
            $faceRecognition = FaceRecognitionModel::create($data);
            DB::commit();
            return $faceRecognition;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save face recognition data: ' . $e->getMessage());
        }
    }

    public function update(FaceRecognitionModel $faceRecognition, array $data)
    {
        // 
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

        $filename = $saneName . '-' . Str::random(10) . '.webp';
        $fullPath = $path . '/' . $filename;

        $image = Image::read($binary)->toWebp($quality);

        Storage::disk('public')->put($fullPath, (string) $image);

        return $fullPath;
    }
}
