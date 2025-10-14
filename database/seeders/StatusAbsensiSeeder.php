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
            ['name' => 'Hadir'],
            ['name' => 'Izin'],
            ['name' => 'Sakit'],
            ['name' => 'Tanpa Keterangan'],
            ['name' => 'Cuti'],
            ['name' => 'Libur'],
            ['name' => 'Pulang'],
        ];
        foreach ($statusAbsensi as $status) {
            StatusAbsensi::create($status);
        }
    }
}
