<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $permissions = Permission::all()->pluck('name')->toArray();
        $roles = [
            'administrator',
            'direktur-bussiness-development',
            'direktur-operasional-legal',
            'direktur-koordinator-cabang',
            'finance-manager',
            'finance-admin',
            'finance-staff',
            'operasional-manager',
            'operasional-admin',
            'operasional-staff',
            'marketing-manager',
            'marketing-admin',
            'marketing-staff',
            'digital-marketing-manager',
            'digital-marketing-admin',
            'digital-marketing-staff',
            'it-manager',
            'it-admin',
            'it-staff',
            'customer-service-manager',
            'customer-service-admin',
            'customer-service-staff',
        ];

        foreach ($roles as $roleName) {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
            if ($roleName === 'administrator') {
                $role->syncPermissions($permissions);
            }
        }
    }
}
