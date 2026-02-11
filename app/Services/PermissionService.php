<?php

namespace App\Services;

use App\Models\Permission;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $data['guard_name'] = 'web';
            $permission = Permission::create($data);
            DB::commit();
            return $permission;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save permission data: ' . $e->getMessage());
        }
    }

    public function update($permission, array $data)
    {
        DB::beginTransaction();
        try {
            //
        } catch (Exception $e) {
            // 
        }
    }
}
