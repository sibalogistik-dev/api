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
        $cabangs = [
            [
                'name' => 'SIBA Cargo Pusat',
                'address' => 'Jl. Raya No.1, Tanjungpinang',
                'telephone' => '0771-12345678',
                'village_id' => 2172021001,
                'company_id' => 1,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 0.9200032040168892,
                'longitude' => 104.48686412750135,
            ],
            [
                'name' => 'SIBA Cargo Tanjungpinang',
                'address' => 'Jl. Raya No.1, Tanjungpinang',
                'telephone' => '0771-87654321',
                'village_id' => 2172021001,
                'company_id' => 1,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 0.9200032040168892,
                'longitude' => 104.48686412750135,
            ],
            [
                'name' => 'SIBA Cargo Tanjung Balai Karimun',
                'address' => 'Jl. Raya No.1, Tanjung Balai Karimun',
                'telephone' => '0771-87654321',
                'village_id' => 2102031008,
                'company_id' => 1,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 0.9200032040168892,
                'longitude' => 104.48686412750135,
            ],
            [
                'name' => 'SIBA Cargo Jakarta Utara',
                'address' => 'Jl. Raya No.1, Jakarta Utara',
                'telephone' => '021-87654321',
                'village_id' => 3172051003,
                'company_id' => 1,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => -6.132674831292673,
                'longitude' => 106.82191124520665,
            ],
            [
                'name' => 'SIBA Cargo Pekanbaru',
                'address' => 'Jl. Raya No.1, Pekanbaru',
                'telephone' => '0761-87654321',
                'village_id' => 1471101003,
                'company_id' => 1,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 0.4137533227836478,
                'longitude' => 101.41881421618604,
            ],
            [
                'name' => 'SIBA Cargo Batam',
                'address' => 'Jl. Raya No.1, Batam',
                'telephone' => '0778-87654321',
                'village_id' => 2171101001,
                'company_id' => 1,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 1.102126369534188,
                'longitude' => 104.05864708608739,
            ],
            [
                'name' => 'Best Furniture Batam',
                'address' => 'Jl. Raya No.1, Batam',
                'telephone' => '0778-87654321',
                'village_id' => 2171091004,
                'company_id' => 2,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 1.17291,
                'longitude' => 104.033672,
            ],
            [
                'name' => 'Men Cargo Tanjungpinang',
                'address' => 'Jl. Raya No.1, Tanjungpinang',
                'telephone' => '0771-87654321',
                'village_id' => 2172021001,
                'company_id' => 3,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 0.9179987899738985,
                'longitude' => 104.48186075135808,
            ],
            [
                'name' => 'Men Cargo Batam',
                'address' => 'Jl. Raya No.1, Batam',
                'telephone' => '0778-87654321',
                'village_id' => 2171101001,
                'company_id' => 3,
                'start_time' => '08:30:00',
                'end_time' => '17:30:00',
                'latitude' => 1.1174840060611266,
                'longitude' => 104.05798763838094,
            ],
            [
                'name' => 'SAuto8 Tanjungpinang',
                'address' => 'Jl. Raya No.1, Tanjungpinang',
                'telephone' => '0771-87654321',
                'village_id' => 2172021001,
                'company_id' => 4,
                'start_time' => '09:00:00',
                'end_time' => '17:30:00',
                'latitude' => 0.9067651739342958,
                'longitude' => 104.49010712598894,
            ],
            [
                'name' => 'MBS Cargo Tanjungpinang',
                'address' => 'Jl. Raya No.1, Tanjungpinang',
                'telephone' => '0771-87654321',
                'village_id' => 2172021001,
                'company_id' => 5,
                'start_time' => '09:00:00',
                'end_time' => '17:30:00',
                'latitude' => 0.9067651739342958,
                'longitude' => 104.49010712598894,
            ],
        ];

        foreach ($cabangs as $cabang) {
            Cabang::create($cabang);
        }
    }
}
