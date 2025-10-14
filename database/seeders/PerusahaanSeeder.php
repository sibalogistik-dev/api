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
            ['name' => 'SIBA Cargo', 'codename' => 'sibacargo'],
            ['name' => 'Best Furniture', 'codename' => 'bestfurniture'],
            ['name' => 'Men Cargo', 'codename' => 'mencargo'],
            ['name' => 'Mabes', 'codename' => 'mabes'],
            ['name' => 'SAuto8', 'codename' => 'sauto8'],
            ['name' => 'MBS Cargo', 'codename' => 'mbscargo'],
        ];

        foreach ($data as $item) {
            Perusahaan::create($item);
        }
    }
}
