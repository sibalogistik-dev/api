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
            /** login permission */
            'login hrd',
            'login karyawan',
            'login marketing',
            'login finance',
            'login vendor',
            'login logistik',
            'login mobile',

            /** hrd app permission */
            // dashboard
            'hrd dashboard',
            // karyawan
            'hrd data karyawan',
            'hrd data karyawan edit',
            'hrd data karyawan create',
            'hrd data karyawan delete',
            'hrd data karyawan print',
            'hrd data gaji',
            'hrd data gaji edit',
            'hrd data gaji create',
            'hrd data gaji delete',
            'hrd data gaji print',
            'hrd data absensi',
            'hrd data absensi edit',
            'hrd data absensi create',
            'hrd data absensi delete',
            'hrd data absensi print',
            'hrd data wfa',
            'hrd data wfa edit',
            'hrd data wfa create',
            'hrd data wfa delete',
            'hrd data laporan harian karyawan',
            'hrd data laporan harian karyawan print',
            'hrd pengumuman karyawan',
            'hrd pengumuman karyawan create',
            // perusahaan
            'hrd data perusahaan',
            'hrd data cabang',
            // aset
            'hrd data pencatatan aset',
            'hrd data pencatatan aset edit',
            'hrd data pencatatan aset create',
            'hrd data pencatatan aset delete',
            'hrd data aset',
            'hrd data aset edit',
            'hrd data aset create',
            'hrd data aset delete',
            'hrd data perbaikan aset',
            // master data
            'hrd data jabatan',
            'hrd data agama',
            'hrd data golongan darah',
            'hrd data status kawin',
            'hrd data pendidikan',
            'hrd data kategori aset',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
