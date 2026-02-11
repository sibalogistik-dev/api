<?php

namespace App\Services;

use App\Models\AssetMaintenance;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class UserPermissionService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            // 
        } catch (Exception $e) {
            // 
        }
    }

    public function update($userPermission, array $data)
    {
        DB::beginTransaction();
        try {
            //
        } catch (Exception $e) {
            // 
        }
    }
}
