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
     * @return void
     */

    public function run(): void
    {
        $permissions = [
            'karyawan app',
            'hrd app',
            'marketing app',
            'finance app',
            'vendor app',
            'logistik app',
            'mobile app',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
