<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::create([
            'nama' => 'SIBA Cargo Pusat',
            'alamat' => 'Jl. Raya No.1, Tanjungpinang',
            'telepon' => '0771-12345678',
            'kota_id' => 2172,
            'perusahaan_id' => 1,
            'latitude' => 0.9200032040168892,
            'longitude' => 104.48686412750135,
        ]);

        Cabang::create([
            'nama' => 'SIBA Cargo Tanjungpinang',
            'alamat' => 'Jl. Raya No.1, Tanjungpinang',
            'telepon' => '0771-87654321',
            'kota_id' => 2172,
            'perusahaan_id' => 1,
            'latitude' => 0.9200032040168892,
            'longitude' => 104.48686412750135,
        ]);

        Cabang::create([
            'nama' => 'SIBA Cargo Jakarta Utara',
            'alamat' => 'Jl. Raya No.1, Jakarta Utara',
            'telepon' => '021-87654321',
            'kota_id' => 3172,
            'perusahaan_id' => 1,
            'latitude' => -6.132674831292673,
            'longitude' => 106.82191124520665,
        ]);

        Cabang::create([
            'nama' => 'SIBA Cargo Pekanbaru',
            'alamat' => 'Jl. Raya No.1, Pekanbaru',
            'telepon' => '0761-87654321',
            'kota_id' => 1471,
            'perusahaan_id' => 1,
            'latitude' => 0.4137533227836478,
            'longitude' => 101.41881421618604,
        ]);

        Cabang::create([
            'nama' => 'SIBA Cargo Batam',
            'alamat' => 'Jl. Raya No.1, Batam',
            'telepon' => '0778-87654321',
            'kota_id' => 2171,
            'perusahaan_id' => 1,
            'latitude' => 1.102126369534188,
            'longitude' => 104.05864708608739,
        ]);

        Cabang::create([
            'nama' => 'Best Furniture Batam',
            'alamat' => 'Jl. Raya No.1, Batam',
            'telepon' => '0778-87654321',
            'kota_id' => 2171,
            'perusahaan_id' => 2,
            'latitude' => 1.138407,
            'longitude' => 104.027791,
        ]);
    }
}
