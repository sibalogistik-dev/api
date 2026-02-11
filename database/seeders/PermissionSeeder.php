<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Module Access
            'hrd'           => 'HRD App Access',
            'employee'      => 'Employee App Access',
            'finance'       => 'Finance App Access',
            'marketing'     => 'Marketing App Access',
            'operational'   => 'Operational App Access',
            'super'         => 'Super App Access',

            // HRD App Permissions
            'hrd.announcement'          => 'HRD Announcement Page Access | Give all access from this section',
            'hrd.announcement.index'    => 'HRD Announcement Page Access | Index',
            'hrd.announcement.show'     => 'HRD Announcement Page Access | Show',
            'hrd.announcement.store'    => 'HRD Announcement Page Access | Create',
            'hrd.announcement.update'   => 'HRD Announcement Page Access | Update',
            'hrd.announcement.destroy'  => 'HRD Announcement Page Access | Delete',

            'hrd.asset-maintenance'         => 'HRD Asset Maintenance Page Access | Give all access from this section',
            'hrd.asset-maintenance.index'   => 'HRD Asset Maintenance Page Access | Index',
            'hrd.asset-maintenance.show'    => 'HRD Asset Maintenance Page Access | Show',
            'hrd.asset-maintenance.store'   => 'HRD Asset Maintenance Page Access | Create',
            'hrd.asset-maintenance.update'  => 'HRD Asset Maintenance Page Access | Update',
            'hrd.asset-maintenance.destroy' => 'HRD Asset Maintenance Page Access | Delete',

            'hrd.attendance'            => 'HRD Absensi Page Access | Give all access from this section',
            'hrd.attendance.index'      => 'HRD Absensi Page Access | Index',
            'hrd.attendance.show'       => 'HRD Absensi Page Access | Show',
            'hrd.attendance.store'      => 'HRD Absensi Page Access | Create',
            'hrd.attendance.update'     => 'HRD Absensi Page Access | Update',
            'hrd.attendance.destroy'    => 'HRD Absensi Page Access | Delete',
            'hrd.attendance.report'     => 'HRD Absensi Page Access | Report',

            'hrd.branch'            => 'HRD Branch Page Access | Give all access from this section',
            'hrd.branch.index'      => 'HRD Branch Page Access | Index',
            'hrd.branch.show'       => 'HRD Branch Page Access | Show',
            'hrd.branch.store'      => 'HRD Branch Page Access | Create',
            'hrd.branch.update'     => 'HRD Branch Page Access | Update',
            'hrd.branch.destroy'    => 'HRD Branch Page Access | Delete',

            'hrd.branch-asset'          => 'HRD Branch Asset Page Access | Give all access from this section',
            'hrd.branch-asset.index'    => 'HRD Branch Asset Page Access | Index',
            'hrd.branch-asset.show'     => 'HRD Branch Asset Page Access | Show',
            'hrd.branch-asset.store'    => 'HRD Branch Asset Page Access | Create',
            'hrd.branch-asset.update'   => 'HRD Branch Asset Page Access | Update',
            'hrd.branch-asset.destroy'  => 'HRD Branch Asset Page Access | Delete',
            'hrd.branch-asset.report'   => 'HRD Branch Asset Page Access | Report',

            'hrd.company'           => 'HRD Company Page Access | Give all access from this section',
            'hrd.company.index'     => 'HRD Company Page Access | Index',
            'hrd.company.show'      => 'HRD Company Page Access | Show',
            'hrd.company.store'     => 'HRD Company Page Access | Create',
            'hrd.company.update'    => 'HRD Company Page Access | Update',
            'hrd.company.destroy'   => 'HRD Company Page Access | Delete',

            'hrd.daily-report'          => 'HRD Daily Report Page Access | Give all access from this section',
            'hrd.daily-report.index'    => 'HRD Daily Report Page Access | Index',
            'hrd.daily-report.show'     => 'HRD Daily Report Page Access | Show',
            'hrd.daily-report.store'    => 'HRD Daily Report Page Access | Create',
            'hrd.daily-report.update'   => 'HRD Daily Report Page Access | Update',
            'hrd.daily-report.destroy'  => 'HRD Daily Report Page Access | Delete',
            'hrd.daily-report.report'   => 'HRD Daily Report Page Access | Report',

            'hrd.employee'          => 'HRD Employee Page Access | Give all access from this section',
            'hrd.employee.index'    => 'HRD Employee Page Access | Index',
            'hrd.employee.show'     => 'HRD Employee Page Access | Show',
            'hrd.employee.store'    => 'HRD Employee Page Access | Create',
            'hrd.employee.update'   => 'HRD Employee Page Access | Update',
            'hrd.employee.destroy'  => 'HRD Employee Page Access | Delete',
            'hrd.employee.restore'  => 'HRD Employee Page Access | Restore',

            'hrd.employee-evaluation'           => 'HRD Employee Evaluation Page Access | Give all access from this section',
            'hrd.employee-evaluation.index'     => 'HRD Employee Evaluation Page Access | Index',
            'hrd.employee-evaluation.show'      => 'HRD Employee Evaluation Page Access | Show',
            'hrd.employee-evaluation.store'     => 'HRD Employee Evaluation Page Access | Create',
            'hrd.employee-evaluation.update'    => 'HRD Employee Evaluation Page Access | Update',
            'hrd.employee-evaluation.destroy'   => 'HRD Employee Evaluation Page Access | Delete',

            'hrd.employee-rolling-schedule'         => 'HRD Employee Rolling Schedule Page Access | Give all access from this section',
            'hrd.employee-rolling-schedule.index'   => 'HRD Employee Rolling Schedule Page Access | Index',
            'hrd.employee-rolling-schedule.show'    => 'HRD Employee Rolling Schedule Page Access | Show',
            'hrd.employee-rolling-schedule.store'   => 'HRD Employee Rolling Schedule Page Access | Create',
            'hrd.employee-rolling-schedule.update'  => 'HRD Employee Rolling Schedule Page Access | Update',
            'hrd.employee-rolling-schedule.destroy' => 'HRD Employee Rolling Schedule Page Access | Delete',

            'hrd.employee-training'             => 'HRD Employee Training Page Access | Give all access from this section',
            'hrd.employee-training.index'       => 'HRD Employee Training Page Access | Index',
            'hrd.employee-training.show'        => 'HRD Employee Training Page Access | Show',
            'hrd.employee-training.store'       => 'HRD Employee Training Page Access | Create',
            'hrd.employee-training.update'      => 'HRD Employee Training Page Access | Update',
            'hrd.employee-training.destroy'     => 'HRD Employee Training Page Access | Delete',
            'hrd.employee-training.report'      => 'HRD Employee Training Page Access | Report',
            'hrd.employee-training.document'    => 'HRD Employee Training Page Access | Document',

            'hrd.employee-training-schedule'            => 'HRD Employee Training Schedule Page Access | Give all access from this section',
            'hrd.employee-training-schedule.index'      => 'HRD Employee Training Schedule Page Access | Index',
            'hrd.employee-training-schedule.show'       => 'HRD Employee Training Schedule Page Access | Show',
            'hrd.employee-training-schedule.store'      => 'HRD Employee Training Schedule Page Access | Create',
            'hrd.employee-training-schedule.update'     => 'HRD Employee Training Schedule Page Access | Update',
            'hrd.employee-training-schedule.destroy'    => 'HRD Employee Training Schedule Page Access | Delete',

            'hrd.employee-training-type'            => 'HRD Employee Training Type Page Access | Give all access from this section',
            'hrd.employee-training-type.index'      => 'HRD Employee Training Type Page Access | Index',
            'hrd.employee-training-type.show'       => 'HRD Employee Training Type Page Access | Show',
            'hrd.employee-training-type.store'      => 'HRD Employee Training Type Page Access | Create',
            'hrd.employee-training-type.update'     => 'HRD Employee Training Type Page Access | Update',
            'hrd.employee-training-type.destroy'    => 'HRD Employee Training Type Page Access | Delete',

            'hrd.face-recognition'          => 'HRD Face Recognition Page Access | Give all access from this section',
            'hrd.face-recognition.index'    => 'HRD Face Recognition Page Access | Index',
            'hrd.face-recognition.show'     => 'HRD Face Recognition Page Access | Show',
            'hrd.face-recognition.store'    => 'HRD Face Recognition Page Access | Create',
            'hrd.face-recognition.update'   => 'HRD Face Recognition Page Access | Update',
            'hrd.face-recognition.destroy'  => 'HRD Face Recognition Page Access | Delete',

            'hrd.holiday'           => 'HRD Holiday Page Access | Give all access from this section',
            'hrd.holiday.index'     => 'HRD Holiday Page Access | Index',
            'hrd.holiday.show'      => 'HRD Holiday Page Access | Show',
            'hrd.holiday.store'     => 'HRD Holiday Page Access | Create',
            'hrd.holiday.update'    => 'HRD Holiday Page Access | Update',
            'hrd.holiday.destroy'   => 'HRD Holiday Page Access | Delete',

            'hrd.job-description'           => 'HRD Job Description Page Access | Give all access from this section',
            'hrd.job-description.index'     => 'HRD Job Description Page Access | Index',
            'hrd.job-description.show'      => 'HRD Job Description Page Access | Show',
            'hrd.job-description.store'     => 'HRD Job Description Page Access | Create',
            'hrd.job-description.update'    => 'HRD Job Description Page Access | Update',
            'hrd.job-description.destroy'   => 'HRD Job Description Page Access | Delete',

            'hrd.job-title'         => 'HRD Job Title Page Access | Give all access from this section',
            'hrd.job-title.index'   => 'HRD Job Title Page Access | Index',
            'hrd.job-title.show'    => 'HRD Job Title Page Access | Show',
            'hrd.job-title.store'   => 'HRD Job Title Page Access | Create',
            'hrd.job-title.update'  => 'HRD Job Title Page Access | Update',
            'hrd.job-title.destroy' => 'HRD Job Title Page Access | Delete',

            'hrd.midday-attendance'         => 'HRD Absensi Siang Page Access | Give all access from this section',
            'hrd.midday-attendance.index'   => 'HRD Absensi Siang Page Access | Index',
            'hrd.midday-attendance.show'    => 'HRD Absensi Siang Page Access | Show',
            'hrd.midday-attendance.store'   => 'HRD Absensi Siang Page Access | Create',
            'hrd.midday-attendance.update'  => 'HRD Absensi Siang Page Access | Update',
            'hrd.midday-attendance.destroy' => 'HRD Absensi Siang Page Access | Delete',

            'hrd.overtime'          => 'HRD Overtime Page Access | Give all access from this section',
            'hrd.overtime.index'    => 'HRD Overtime Page Access | Index',
            'hrd.overtime.show'     => 'HRD Overtime Page Access | Show',
            'hrd.overtime.store'    => 'HRD Overtime Page Access | Create',
            'hrd.overtime.update'   => 'HRD Overtime Page Access | Update',
            'hrd.overtime.destroy'  => 'HRD Overtime Page Access | Delete',
            'hrd.overtime.report'   => 'HRD Overtime Page Access | Report',

            'hrd.payroll'           => 'HRD Payroll Page Access | Give all access from this section',
            'hrd.payroll.index'     => 'HRD Payroll Page Access | Index',
            'hrd.payroll.show'      => 'HRD Payroll Page Access | Show',
            'hrd.payroll.store'     => 'HRD Payroll Page Access | Create',
            'hrd.payroll.update'    => 'HRD Payroll Page Access | Update',
            'hrd.payroll.destroy'   => 'HRD Payroll Page Access | Delete',
            'hrd.payroll.report'    => 'HRD Payroll Page Access | Report',
            'hrd.payroll.slip'      => 'HRD Payroll Page Access | Slip',

            'hrd.permission'           => 'HRD Permission Page Access | Give all access from this section',
            'hrd.permission.index'     => 'HRD Permission Page Access | Index',
            'hrd.permission.show'      => 'HRD Permission Page Access | Show',
            'hrd.permission.store'     => 'HRD Permission Page Access | Create',
            'hrd.permission.update'    => 'HRD Permission Page Access | Update',
            'hrd.permission.destroy'   => 'HRD Permission Page Access | Delete',

            'hrd.remote-attendance'         => 'HRD Remote Attendance Page Access | Give all access from this section',
            'hrd.remote-attendance.index'   => 'HRD Remote Attendance Page Access | Index',
            'hrd.remote-attendance.show'    => 'HRD Remote Attendance Page Access | Show',
            'hrd.remote-attendance.store'   => 'HRD Remote Attendance Page Access | Create',
            'hrd.remote-attendance.update'  => 'HRD Remote Attendance Page Access | Update',
            'hrd.remote-attendance.destroy' => 'HRD Remote Attendance Page Access | Delete',

            'hrd.reprimand-letter'          => 'HRD Reprimand Letter Page Access | Give all access from this section',
            'hrd.reprimand-letter.index'    => 'HRD Reprimand Letter Page Access | Index',
            'hrd.reprimand-letter.show'     => 'HRD Reprimand Letter Page Access | Show',
            'hrd.reprimand-letter.store'    => 'HRD Reprimand Letter Page Access | Create',
            'hrd.reprimand-letter.update'   => 'HRD Reprimand Letter Page Access | Update',
            'hrd.reprimand-letter.destroy'  => 'HRD Reprimand Letter Page Access | Delete',
            'hrd.reprimand-letter.document' => 'HRD Reprimand Letter Page Access | Document',
            'hrd.reprimand-letter.report'   => 'HRD Reprimand Letter Page Access | Report',

            'hrd.resign'            => 'HRD Resign Page Access | Give all access from this section',
            'hrd.resign.index'      => 'HRD Resign Page Access | Index',
            'hrd.resign.show'       => 'HRD Resign Page Access | Show',
            'hrd.resign.store'      => 'HRD Resign Page Access | Create',
            'hrd.resign.update'     => 'HRD Resign Page Access | Update',
            'hrd.resign.destroy'    => 'HRD Resign Page Access | Delete',

            'hrd.user-permission'           => 'HRD User Permission Page Access | Give all access from this section',
            'hrd.user-permission.index'     => 'HRD User Permission Page Access | Index',
            'hrd.user-permission.show'      => 'HRD User Permission Page Access | Show',
            'hrd.user-permission.store'     => 'HRD User Permission Page Access | Create',
            'hrd.user-permission.update'    => 'HRD User Permission Page Access | Update',
            'hrd.user-permission.destroy'   => 'HRD User Permission Page Access | Delete',

            'hrd.warning-letter'            => 'HRD Warning Letter Page Access | Give all access from this section',
            'hrd.warning-letter.index'      => 'HRD Warning Letter Page Access | Only Index',
            'hrd.warning-letter.show'       => 'HRD Warning Letter Page Access | Only Show',
            'hrd.warning-letter.store'      => 'HRD Warning Letter Page Access | Only Create',
            'hrd.warning-letter.update'     => 'HRD Warning Letter Page Access | Only Update',
            'hrd.warning-letter.destroy'    => 'HRD Warning Letter Page Access | Only Delete',
            'hrd.warning-letter.document'   => 'HRD Warning Letter Page Access | Only Document',
            'hrd.warning-letter.report'     => 'HRD Warning Letter Page Access | Only Report',
        ];

        foreach ($permissions as $permissionName => $description) {
            Permission::updateOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ], [
                'description' => $description
            ]);
        }
    }
}
