<?php

namespace Database\Seeders;

use App\Models\BranchAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchAssetSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'branch_id' => 1,
                'asset_type_id' => 3,
                'is_vehicle' => true,
                'name' => 'Mobil Operasional Cabang A',
                'description' => 'Mobil operasional untuk keperluan cabang A',
            ],
            [
                'branch_id' => 2,
                'asset_type_id' => 4,
                'is_vehicle' => true,
                'name' => 'Motor Kurir Cabang B',
                'description' => 'Motor untuk keperluan kurir di cabang B',
            ]
        ];

        foreach ($data as $item) {
            BranchAsset::create($item);
        }
    }
}
