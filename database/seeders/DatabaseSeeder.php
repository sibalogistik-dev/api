<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgamaSeeder::class,
            JabatanSeeder::class,
            PendidikanSeeder::class,
            AssetTypeSeeder::class,
            MarriageStatusSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,

            UserSeeder::class,
            CabangSeeder::class,
            PerusahaanSeeder::class,
            KaryawanSeeder::class,
            RemoteAttendanceSeeder::class,

            StatusAbsensiSeeder::class,
            AbsensiSeeder::class,
            OvertimeSeeder::class,
            JobDescriptionSeeder::class,
        ]);
    }
}
