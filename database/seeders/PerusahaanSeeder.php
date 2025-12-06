<?php

namespace Database\Seeders;

use App\Models\Perusahaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'          => 'SIBA Cargo',
                'codename'      => 'sibacargo',
                'email'         => 'info@sibacargo.com',
                'website'       => 'www.sibalogistik.com',
                'company_brand' => 'PT Wajah Siba Nusantara'
            ],
            [
                'name'          => 'Best Pro Logistik',
                'codename'      => 'bestprologistik',
                'email'         => 'info@bestprologistic.com',
                'website'       => 'www.bestprologistic.com',
                'company_brand' => 'PT Bestindo Projek Logistik'
            ],
            [
                'name'          => 'Men Cargo',
                'codename'      => 'mencargo',
                'email'         => 'info@mencargo.id',
                'website'       => 'www.mencargo.id',
                'company_brand' => 'CV Manna Ekspedisi Nauli'
            ],
            [
                'name'          => 'SAuto8',
                'codename'      => 'sauto8',
                'email'         => 'info@mbscargo.id',
                'website'       => 'www.mbscargo.id',
                'company_brand' => 'SIBA'
            ],
            [
                'name'          => 'MBS Cargo',
                'codename'      => 'mbscargo',
                'email'         => 'info@mbscargo.id',
                'website'       => 'www.mbscargo.id',
                'company_brand' => 'SIBA'
            ],
        ];

        foreach ($data as $item) {
            Perusahaan::create($item);
        }
    }
}
