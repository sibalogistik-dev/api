<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Manager Operasional dan HRD',
            'Staff Operasional',
            'Staff HRD',
            'Staff IT',
            'Customer Service',
            'Manager Marketing',
            'Manager Digital Marketing',
            'Staff Digital Marketing',
            'Staff Branding',
            'Manager Finance',
            'Staff Finance',
            'Staff Accounting',
            'Staff Tracking',
            'Staff Purchasing',
        ];

        for ($i = 0; $i < count($roles); $i++) {
            $role = Role::create([
                'name' => $roles[$i],
            ]);
            if ($roles[$i] == 'Super Admin') {
                $role->givePermissionTo(Permission::all());
            }
        }
    }
}
