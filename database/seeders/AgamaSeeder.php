<?php

namespace Database\Seeders;

use App\Models\Agama;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agama = [
            'Islam',
            'Katolik',
            'Protestan',
            'Hindu',
            'Buddha',
            'Khonghucu',
        ];

        for ($i = 0; $i < count($agama); $i++) {
            Agama::create([
                'nama' => $agama[$i]
            ]);
        }
    }
}
