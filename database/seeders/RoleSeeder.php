<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $karyawanPermissions = [
            'app.access.karyawan',
            'employees.view.self',
            'employees.edit.self',
            'employees.salary.view.self',
            'employees.pii.view.self',
            'attendance.create.self',
            'attendance.view.self',
            'leave.request.self',
            'leave.view.self',
        ];

        $managerPermissions = array_merge($karyawanPermissions, [
            'app.access.hrd',
            'employees.view.team',
            'attendance.view.team',
            'leave.view.team',
            'leave.approve.team',
        ]);

        $hrdPermissions = array_merge($karyawanPermissions, [
            'app.access.hrd',
            'employees.view.all',
            'employees.create',
            'employees.edit.all',
            'employees.delete',
            'employees.pii.view.all',
            'employees.pii.edit.all',
            'attendance.view.all',
            'attendance.edit',
            'attendance.approve',
            'leave.view.all',
            'leave.approve.all',
            'company.branch.manage',
            'company.department.manage',
            'company.job_title.manage',
            'assets.view',
            'assets.create',
            'assets.edit',
            'assets.delete',
            'assets.manage.requests',
            'system.users.manage',
        ]);

        $financePermissions = array_merge($karyawanPermissions, [
            'app.access.finance',
            'employees.view.all',
            'employees.pii.view.all',
            'employees.salary.view.all',
            'employees.salary.edit',
        ]);

        $direkturPermissions = array_merge($karyawanPermissions, [
            'app.access.hrd',
            'app.access.finance',
            'app.access.logistik',
            'app.access.marketing',
            'employees.view.all',
            'employees.salary.view.all',
            'attendance.view.all',
            'leave.view.all',
            'leave.approve.all',
            'assets.view',
        ]);

        $logistikPermissions = array_merge($karyawanPermissions, [
            'app.access.logistik',
            'assets.view',
            'assets.create',
            'assets.edit',
            'assets.delete',
            'assets.manage.requests',
        ]);

        $marketingPermissions = array_merge($karyawanPermissions, [
            'app.access.marketing',
        ]);

        $roleKaryawan = Role::firstOrCreate(['name' => 'Karyawan']);
        $roleKaryawan->syncPermissions($karyawanPermissions);

        $roleManager = Role::firstOrCreate(['name' => 'Manager']);
        $roleManager->syncPermissions($managerPermissions);

        $roleHRD = Role::firstOrCreate(['name' => 'HRD']);
        $roleHRD->syncPermissions($hrdPermissions);

        $roleFinance = Role::firstOrCreate(['name' => 'Finance']);
        $roleFinance->syncPermissions($financePermissions);

        $roleDirektur = Role::firstOrCreate(['name' => 'Direktur']);
        $roleDirektur->syncPermissions($direkturPermissions);

        $roleLogistik = Role::firstOrCreate(['name' => 'Logistik']);
        $roleLogistik->syncPermissions($logistikPermissions);

        $roleMarketing = Role::firstOrCreate(['name' => 'Marketing']);
        $roleMarketing->syncPermissions($marketingPermissions);
    }
}
