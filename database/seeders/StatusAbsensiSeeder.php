<?php

namespace Database\Seeders;

use App\Models\StatusAbsensi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusAbsensi = [
            ['name' => 'Hadir'],            // 1
            ['name' => 'Izin'],             // 2
            ['name' => 'Sakit'],            // 3
            ['name' => 'Tanpa Keterangan'], // 4
            ['name' => 'Cuti'],             // 5
            ['name' => 'Libur'],            // 6
        ];
        foreach ($statusAbsensi as $status) {
            StatusAbsensi::create($status);
        }
    }
}
