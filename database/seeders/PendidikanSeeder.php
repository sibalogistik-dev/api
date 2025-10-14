<?php

namespace Database\Seeders;

use App\Models\Pendidikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikan = [
            'PAUD',
            'SD',
            'SMP',
            'SMA',
            'Sarjana',
            'Magister',
            'Doktor',
        ];

        for ($i = 0; $i < count($pendidikan); $i++) {
            Pendidikan::create([
                'name' => $pendidikan[$i]
            ]);
        }
    }
}
