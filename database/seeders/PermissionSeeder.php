<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeder for permissions.
     * 
     * This seeder creates basic CRUD permissions for various entities:
     * - Jabatan (Position/Role)
     * - Karyawan (Employee)
     * - Perusahaan (Company)
     * - Cabang (Branch)
     * - Roles
     * - Permissions
     * 
     * For each entity, it creates the following permissions:
     * - index (list/view all)
     * - create
     * - show (view single)
     * - update
     * - destroy (delete)
     *
     * @return void
     */
    
    public function run(): void
    {
        $permissions = [
            'jabatan index',
            'jabatan create',
            'jabatan show',
            'jabatan update',
            'jabatan destroy',
            'karyawan index',
            'karyawan create',
            'karyawan show',
            'karyawan update',
            'karyawan destroy',
            'perusahaan index',
            'perusahaan create',
            'perusahaan show',
            'perusahaan update',
            'perusahaan destroy',
            'cabang index',
            'cabang create',
            'cabang show',
            'cabang update',
            'cabang destroy',
            
            // roles and permissions 
            'role index',
            'role create',
            'role show',
            'role update',
            'role destroy',
            'permission index',
            'permission create',
            'permission show',
            'permission update',
            'permission destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
