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
            ['nama' => 'Hadir'],
            ['nama' => 'Izin'],
            ['nama' => 'Sakit'],
            ['nama' => 'Tanpa Keterangan'],
            ['nama' => 'Cuti'],
            ['nama' => 'Libur'],
            ['nama' => 'Pulang'],
        ];
        foreach ($statusAbsensi as $status) {
            StatusAbsensi::create($status);
        }
    }
}
