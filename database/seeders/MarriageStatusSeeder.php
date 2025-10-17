<?php

namespace Database\Seeders;

use App\Models\MarriageStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarriageStatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'belum kawin',
            'kawin',
            'duda',
            'janda',
        ];
        for ($i = 0; $i < count($data); $i++) {
            MarriageStatus::create([
                'name' => $data[$i]
            ]);
        }
    }
}
