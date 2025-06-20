<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgamaSeeder::class,
            PendidikanSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,

            UserSeeder::class,
            CabangSeeder::class,
            PerusahaanSeeder::class,
            JabatanSeeder::class,
            KaryawanSeeder::class,
        ]);
    }
}
