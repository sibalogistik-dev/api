<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Cabang::create([
            'nama' => 'Siba Cargo Pusat',
            'alamat' => 'Jl. Raya No.1, Tanjungpinang',
            'telepon' => '0771-12345678',
            'kota_id' => 2172,
            'perusahaan_id' => 1,
            'latitude' => 0.9200032040168892,
            'longitude' => 104.48686412750135,
        ]);

        Cabang::create([
            'nama' => 'Siba Cargo Tanjungpinang',
            'alamat' => 'Jl. Raya No.1, Tanjungpinang',
            'telepon' => '0771-87654321',
            'kota_id' => 2172,
            'perusahaan_id' => 1,
            'latitude' => 0.9200032040168892,
            'longitude' => 104.48686412750135,
        ]);
    }
}
