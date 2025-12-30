<?php

namespace App\Services;

use App\Models\Agama;
use App\Models\AssetMaintenance;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AssetMaintenanceService
{
    public function create(array $data)
    {
        // return DB::transaction(function () use ($data) {
        //     return AssetMaintenance::create($data);
        // });
        $filePaths = [];
        DB::beginTransaction();
        try {
            $uploads = $data;
            if (!empty($data['receipt'])) {
                $filePaths['receipt'] = $this->storeFile($data['receipt'], 'uploads/asset_maintenance_receipts', 90);
                $uploads['receipt'] = $filePaths['receipt'];
            }
            $assetMaintenance = AssetMaintenance::create($uploads);
            DB::commit();
            return $assetMaintenance;
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($filePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw new Exception('Failed to save asset maintenance data: ' . $e->getMessage());
        }
    }

    public function update(AssetMaintenance $assetMaintenance, array $data)
    {
        // return DB::transaction(function () use ($assetMaintenance, $data) {
        //     $assetMaintenance->update($data);
        //     return $assetMaintenance;
        // });
        $filePaths = [];
        DB::beginTransaction();
        try {
            $uploads = $data;
            if (!empty($data['receipt'])) {
                $filePaths['receipt'] = $this->storeFile($data['receipt'], 'uploads/asset_maintenance_receipts', 90);
                $uploads['receipt'] = $filePaths['receipt'];
            }
            $assetMaintenance->update($uploads);
            DB::commit();
            return $assetMaintenance;
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($filePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw new Exception('Failed to update asset maintenance data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path, int $quality = 90)
    {
        $isImage    = Str::startsWith($file->getMimeType(), 'image/');

        if ($isImage) {
            $filename = date('Ymd-His') . '-' . Str::random(10) . '.png';
            $fullPath = $path . '/' . $filename;

            $imageContent = Image::read($file->getRealPath())->toPng($quality);
            Storage::disk('public')->put($fullPath, (string) $imageContent);

            return $fullPath;
        } else {
            $extension = $file->getClientOriginalExtension();
            $filename  = date('Ymd-His') . '-' . Str::random(10) . '.' . $extension;
            $fullPath  = $path . '/' . $filename;

            Storage::disk('public')->putFileAs($path, $file, $filename);

            return $fullPath;
        }
    }
}
