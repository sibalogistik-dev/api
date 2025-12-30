<?php

namespace App\Services;

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
        $filePaths = [];
        DB::beginTransaction();
        try {
            $uploads = $data;
            $filePaths['receipt']    = 'uploads/asset_maintenance_receipts/default.webp';
            if (!empty($data['receipt'])) {
                $filePaths['receipt'] = $this->storeFile($data['receipt'], 'uploads/asset_maintenance_receipts', 90);
                $uploads['receipt'] = $filePaths['receipt'];
            }
            $assetMaintenance = AssetMaintenance::create($uploads);
            DB::commit();
            return $assetMaintenance;
        } catch (Exception $e) {
            DB::rollBack();
            if ($filePaths !== '') {
                foreach ($filePaths as $path) {
                    if ($path) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            throw new Exception('Failed to save asset maintenance data: ' . $e->getMessage());
        }
    }

    public function update(AssetMaintenance $assetMaintenance, array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            $uploads = $data;
            if (!empty($data['receipt'])) {
                $filePaths['receipt']   = $this->storeFile($data['receipt'], 'uploads/asset_maintenance_receipts', 90);
                $uploads['receipt']     = $filePaths['receipt'];
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
