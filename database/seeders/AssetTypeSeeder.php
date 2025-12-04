<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Faker\Provider\Lorem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes\Fake;

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
                'name' => $data[$i],
                'description' => Lorem::sentence(rand(6, 10)),
            ]);
        }
    }
}
