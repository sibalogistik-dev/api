<?php

namespace App\Services;

use App\Models\BranchAsset;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class BranchAssetService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        $filePaths = [];
        try {
            if (isset($data['image_path']) && !empty($data['image_path'])) {
                $filePaths['image_path']    = $this->storeFile($data['image_path'], 'uploads/branch_asset');
            }
            $branchAsset = BranchAsset::create([
                'branch_id'     => $data['branch_id'],
                'asset_type_id' => $data['asset_type_id'],
                'is_vehicle'    => $data['is_vehicle'] ?? false,
                'name'          => $data['name'],
                'price'         => $data['price'],
                'quantity'      => $data['quantity'],
                'image_path'    => $filePaths['image_path'] ?? null,
                'purchase_date' => $data['purchase_date'] ?? null,
                'description'   => $data['description'] ?? null,
            ]);
            DB::commit();
            return $branchAsset;
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($filePaths as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            throw new Exception('Failed to save branch\'s asset data: ' . $e->getMessage());
        }
    }

    public function update(BranchAsset $branchAsset, array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['image_path']) && !empty($data['image_path'])) {
                $filePaths['image_path']    = $this->storeFile($data['image_path'], 'uploads/branch_asset');
                $data['image_path']         = $filePaths['image_path'];
                if (!empty($branchAsset->image_path) && Storage::disk('public')->exists($branchAsset->image_path)) {
                    Storage::disk('public')->delete($branchAsset->image_path);
                }
            }
            $branchAsset->update($data);
            DB::commit();
            return $branchAsset;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch\'s asset data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path, int $quality = 90)
    {
        $isImage = Str::startsWith($file->getMimeType(), 'image/');

        if ($isImage) {
            $filename = date('Ymd-His') . '-' . Str::random(10) . '.webp';
            $fullPath = $path . '/' . $filename;

            $imageContent = Image::read($file->getRealPath())->toWebp($quality);
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
