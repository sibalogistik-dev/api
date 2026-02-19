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
        $superAdmin->syncPermissions([
            'hrd',
            'hrd.announcement',
            'hrd.asset-maintenance',
            'hrd.attendance',
            'hrd.branch',
            'hrd.branch-asset',
            'hrd.company',
            'hrd.daily-report',
            'hrd.employee',
            'hrd.employee-evaluation',
            'hrd.employee-rolling-schedule',
            'hrd.employee-training',
            'hrd.employee-training-schedule',
            'hrd.employee-training-type',
            'hrd.face-recognition',
            'hrd.holiday',
            'hrd.job-description',
            'hrd.job-title',
            'hrd.midday-attendance',
            'hrd.overtime',
            'hrd.payroll',
            'hrd.permission',
            'hrd.remote-attendance',
            'hrd.reprimand-letter',
            'hrd.resign',
            'hrd.user-permission',
            'hrd.warning-letter',
        ]);

        $karyawanPermissions = [
            'employee'
        ];

        $managerPermissions = array_merge($karyawanPermissions, [
            'employee'
        ]);

        $hrdPermissions = array_merge($karyawanPermissions, [
            'hrd',
            'hrd.announcement',
            'hrd.asset-maintenance',
            'hrd.attendance',
            'hrd.branch',
            'hrd.branch-asset',
            'hrd.company',
            'hrd.daily-report',
            'hrd.employee',
            'hrd.employee-evaluation',
            'hrd.employee-rolling-schedule',
            'hrd.employee-training',
            'hrd.employee-training-schedule',
            'hrd.employee-training-type',
            'hrd.face-recognition',
            'hrd.holiday',
            'hrd.job-description',
            'hrd.job-title',
            'hrd.overtime',
            'hrd.payroll',
            'hrd.remote-attendance',
            'hrd.reprimand-letter',
            'hrd.resign',
            'hrd.warning-letter',
        ]);

        $financePermissions = array_merge($karyawanPermissions, [
            'finance'
        ]);

        $direkturPermissions = [
            'hrd',
            'hrd.announcement.index',
            'hrd.announcement.show',
            'hrd.asset-maintenance.index',
            'hrd.asset-maintenance.show',
            'hrd.attendance.index',
            'hrd.attendance.show',
            'hrd.branch.index',
            'hrd.branch.show',
            'hrd.branch-asset.index',
            'hrd.branch-asset.show',
            'hrd.company.index',
            'hrd.company.show',
            'hrd.daily-report.index',
            'hrd.daily-report.show',
            'hrd.employee.index',
            'hrd.employee.show',
            'hrd.employee-evaluation.index',
            'hrd.employee-evaluation.show',
            'hrd.employee-rolling-schedule.index',
            'hrd.employee-rolling-schedule.show',
            'hrd.employee-training.index',
            'hrd.employee-training.show',
            'hrd.employee-training-schedule.index',
            'hrd.employee-training-schedule.show',
            'hrd.employee-training-type.index',
            'hrd.employee-training-type.show',
            'hrd.face-recognition.index',
            'hrd.face-recognition.show',
            'hrd.holiday.index',
            'hrd.holiday.show',
            'hrd.job-description.index',
            'hrd.job-description.show',
            'hrd.job-title.index',
            'hrd.job-title.show',
            'hrd.overtime.index',
            'hrd.overtime.show',
            'hrd.payroll.index',
            'hrd.payroll.show',
            'hrd.remote-attendance.index',
            'hrd.remote-attendance.show',
            'hrd.reprimand-letter.index',
            'hrd.reprimand-letter.show',
            'hrd.resign.index',
            'hrd.resign.show',
            'hrd.warning-letter.index',
            'hrd.warning-letter.show',

            'super',
            'operational',
            'finance',
            'marketing',
        ];

        $logistikPermissions = array_merge($karyawanPermissions, [
            'operational'
        ]);

        $marketingPermissions = array_merge($karyawanPermissions, [
            'marketing'
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
