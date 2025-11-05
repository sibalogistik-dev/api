<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Alat Gudang',
            'Alat Kantor',
            'Mobil',
            'Motor',
            'Lainnya',
        ];

        for ($i = 0; $i < count($data); $i++) {
            AssetType::create([
                'name' => $data[$i]
            ]);
        }
    }
}
