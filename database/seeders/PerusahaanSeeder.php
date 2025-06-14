<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'SIBA Cargo', 'codename' => 'siba'],
            ['nama' => 'Best Furniture', 'codename' => 'best'],
            ['nama' => 'Men Cargo', 'codename' => 'mencargo'],
            ['nama' => 'Mabes', 'codename' => 'mabes'],
            ['nama' => 'SAuto8', 'codename' => 'sauto8'],
        ];

        foreach ($data as $item) {
            Perusahaan::create($item);
        }
    }
}
