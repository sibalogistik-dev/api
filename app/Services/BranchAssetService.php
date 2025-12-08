<?php

namespace App\Services;

use App\Models\BranchAsset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class BranchAssetService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            if (!empty($data['image_path'])) {
                $filePaths['image_path']    = $this->storeBase64Image($data['image_path'], 'uploads/branch_asset');
                $data['image_path']         = $filePaths['image_path'];
            } else {
                throw new Exception('Image path is required');
            }
            $branchAsset = BranchAsset::create($data);
            DB::commit();
            return $branchAsset;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save branch\'s asset data: ' . $e->getMessage());
        }
    }

    public function update(BranchAsset $branchAsset, array $data)
    {
        DB::beginTransaction();
        try {
            if (!empty($data['image_path'])) {
                $filePaths['image_path']    = $this->storeBase64Image($data['image_path'], 'uploads/branch_asset');
                $data['image_path']         = $filePaths['image_path'];
                if (!empty($branchAsset->image_path) && Storage::disk('public')->exists($branchAsset->image_path)) {
                    Storage::disk('public')->delete($branchAsset->image_path);
                }
            } else {
                throw new Exception('Image path is required');
            }
            $branchAsset->update($data);
            DB::commit();
            return $branchAsset;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch\'s asset data: ' . $e->getMessage());
        }
    }

    private function storeBase64Image(string $base64, string $path, int $quality = 90)
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64)) {
            throw new Exception("Invalid base64 image format");
        }

        $data = substr($base64, strpos($base64, ',') + 1);
        $binary = base64_decode($data);

        if ($binary === false) {
            throw new Exception("Failed to decode base64 image");
        }

        $filename = Str::random(10) . '.webp';
        $fullPath = $path . '/' . $filename;

        $image = Image::read($binary)->toWebp($quality);

        Storage::disk('public')->put($fullPath, (string) $image);

        return $fullPath;
    }
}
