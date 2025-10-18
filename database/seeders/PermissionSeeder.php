<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Modul Karyawan (Dasar)
            'employees.view.self'   => 'Melihat data karyawan diri sendiri',
            'employees.view.team'   => 'Melihat data karyawan bawahan (tim)',
            'employees.view.all'    => 'Melihat data SEMUA karyawan',
            'employees.create'      => 'Membuat data karyawan baru',
            'employees.edit.self'   => 'Mengedit data karyawan diri sendiri',
            'employees.edit.team'   => 'Mengedit data karyawan bawahan (tim)',
            'employees.edit.all'    => 'Mengedit data SEMUA karyawan',
            'employees.delete'      => 'Menonaktifkan/menghapus data karyawan',

            // Modul Karyawan (Sensitif) - DIPERBAIKI
            'employees.salary.view.self'    => 'Melihat data gaji diri sendiri',
            'employees.salary.view.all'     => 'Melihat data gaji SEMUA karyawan',
            'employees.salary.edit'         => 'Mengubah/menginput data gaji karyawan',
            'employees.pii.view.self'       => 'Melihat data sensitif (KTP, Rek) diri sendiri',
            'employees.pii.view.all'        => 'Melihat data sensitif (KTP, Rek) SEMUA karyawan', // <--- DIPERBAIKI
            'employees.pii.edit.all'        => 'Mengubah data sensitif (KTP, Rek) karyawan', // <--- DIPERBAIKI

            // Modul Absensi
            'attendance.create.self'    => 'Melakukan Clock-in / Clock-out',
            'attendance.view.self'      => 'Melihat riwayat absensi diri sendiri',
            'attendance.view.team'      => 'Melihat riwayat absensi bawahan (tim)',
            'attendance.view.all'       => 'Melihat riwayat absensi SEMUA karyawan',
            'attendance.edit'           => 'Mengoreksi log absensi karyawan',
            'attendance.approve'        => 'Menyetujui pengajuan koreksi absensi',

            // Modul Cuti (Leave)
            'leave.request.self'    => 'Mengajukan cuti',
            'leave.view.self'       => 'Melihat riwayat & sisa cuti diri sendiri',
            'leave.view.team'       => 'Melihat riwayat cuti bawahan (tim)',
            'leave.view.all'        => 'Melihat laporan cuti SEMUA karyawan',
            'leave.approve.team'    => 'Menyetujui/menolak cuti bawahan (tim)',
            'leave.approve.all'     => 'Menyetujui/menolak cuti (level HRD/Direktur)',

            // Modul Perusahaan (Data Master)
            'company.branch.manage'     => 'Mengelola data master Cabang',
            'company.department.manage' => 'Mengelola data master Departemen',
            'company.job_title.manage'  => 'Mengelola data master Jabatan',

            // Modul Aset
            'assets.view'               => 'Melihat daftar aset perusahaan',
            'assets.create'             => 'Menambahkan data aset baru',
            'assets.edit'               => 'Mengedit data aset',
            'assets.delete'             => 'Menghapus data aset',
            'assets.manage.requests'    => 'Mengelola permintaan perbaikan/peminjaman aset',

            // Modul Sistem (Super Admin)
            'system.users.manage'   => 'Mengelola akun login (tabel users)',
            'system.roles.manage'   => 'Mengelola Peran & Izin (Spatie)',

            // Modul Akses Aplikasi
            'app.access.karyawan'   => 'Hak untuk mengakses Aplikasi Karyawan (Self-Service)',
            'app.access.hrd'        => 'Hak untuk mengakses Aplikasi HRD',
            'app.access.finance'    => 'Hak untuk mengakses Aplikasi Finance',
            'app.access.logistik'   => 'Hak untuk mengakses Aplikasi Logistik (Aset/Gudang)',
            'app.access.marketing'  => 'Hak untuk mengakses Aplikasi Marketing (CRM)',
        ];

        foreach ($permissions as $permissionName => $description) {
            Permission::updateOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }
    }
}
