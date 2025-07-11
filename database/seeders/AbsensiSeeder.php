<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataAbsen = [
            [
                'user_id' => 1,
                'tanggal' => now()->toDateString(),
                'jam' => '08:45:32',
            ],
            [
                'user_id' => 2,
                'tanggal' => now()->toDateString(),
                'jam' => '08:55:12',
            ]
        ];
        foreach ($dataAbsen as $data) {
            $kry = Karyawan::find($data['user_id']);
            $lat = $kry->latitude ?? '0';
            $long = $kry->longitude ?? '0';
            Absensi::create([
                'karyawan_id' => $kry->id,
                'status_id' => $data['status_id'] ?? 1,
                'tanggal' => $data['tanggal'],
                'jam' => $data['jam'],
                'img_absensi' => null,
                'keterangan' => null,
                'longitude' => $long,
                'latitude' => $lat,
            ]);
        }
    }
}
