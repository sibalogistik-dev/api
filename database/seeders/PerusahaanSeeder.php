<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Perusahaan::create([
            'nama' => 'SIBA Cargo',
        ]);

        Perusahaan::create([
            'nama' => 'Best Furniture',
        ]);

        Perusahaan::create([
            'nama' => 'Men Cargo',
        ]);

        Perusahaan::create([
            'nama' => 'Mabes',
        ]);

        Perusahaan::create([
            'nama' => 'SAuto8',
        ]);
    }
}
