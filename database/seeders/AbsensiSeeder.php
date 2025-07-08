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
                'jam_masuk' => '08:55:34',
            ],
            [
                'user_id' => 2,
                'tanggal' => now()->toDateString(),
                'jam_masuk' => '08:15:32',
                'jam_keluar' => null,
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
                'jam_masuk' => $data['jam_masuk'],
                'jam_keluar' => $data['jam_keluar'] ?? null,
                'img_absensi' => null,
                'keterangan' => null,
                'longitude' => $long,
                'latitude' => $lat,
            ]);
        }
    }
}
