<?php

namespace Database\Seeders;

use App\Models\{DetailDiri, DetailGaji, DetailKaryawan, Karyawan, User};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id'               => 2,
                'name'                  => 'Mohammad Farid Hasymi',
                'npk'                   => '199708170073',
                'branch_id'             => 1,
                'job_title_id'          => 3,
                'start_date'            => '2025-04-14',
                'bank_account_number'   => '017401106946503',
                'detail_diri'           => [
                    'employee_id'           => 1,
                    'gender'                => 'laki-laki',
                    'religion_id'           => 1,
                    'phone_number'          => '082283784873',
                    'place_of_birth_id'     => 2172,
                    'date_of_birth'         => '1997-08-17',
                    'address'               => 'Jl. Kota Piring',
                    'blood_type'            => 'ab',
                    'education_id'          => 5,
                    'marriage_status'       => 'belum kawin',
                    'residential_area_id'   => 2172,
                ],
                'detail_gaji' => [
                    [
                        'employee_id'           => 1,
                        'monthly_base_salary'   => 2500000,
                        'daily_base_salary'     => 96153,
                        'monthly_base_salary'   => 0,
                        'meal_allowance'        => 0,
                        'bonus'                 => 0,
                        'allowance'             => 0,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ],
                    [
                        'employee_id'           => 1,
                        'monthly_base_salary'   => 3000000,
                        'daily_base_salary'     => 115384,
                        'monthly_base_salary'   => 0,
                        'meal_allowance'        => 0,
                        'bonus'                 => 0,
                        'allowance'             => 0,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ],
                ],
            ],
            [
                'user_id'               => 3,
                'name'                  => 'Toby Fiski',
                'npk'                   => '199905120010',
                'branch_id'             => 2,
                'job_title_id'          => 39,
                'start_date'            => '2018-07-01',
                'bank_account_number'   => '017401089349503',
                'detail_diri'           => [
                    'employee_id'           => 2,
                    'gender'                => 'laki-laki',
                    'religion_id'           => 1,
                    'phone_number'          => '082387301492',
                    'place_of_birth_id'     => 2172,
                    'date_of_birth'         => '1999-05-12',
                    'address'               => 'JL. Karya Perum. Griya Pinang Asri (Tanjungpinang)',
                    'blood_type'            => 'a',
                    'education_id'          => 4,
                    'marriage_status'       => 'kawin',
                    'residential_area_id'   => 2172,
                ],
                'detail_gaji' => [
                    [
                        'employee_id'           => 2,
                        'monthly_base_salary'   => 4050000,
                        'daily_base_salary'     => 50000,
                        'meal_allowance'        => 15000,
                        'bonus'                 => 20000,
                        'allowance'             => 70769,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]
                ],
            ],
        ];

        for ($i = 0; $i < count($data); $i++) {
            $user = User::create([
                'name'              => $data[$i]['name'],
                'email'             => str_replace(' ', '', strtolower($data[$i]['name'])) . '@gmail.com',
                'username'          => str_replace(' ', '', strtolower($data[$i]['name'])),
                'password'          => str_replace(' ', '', strtolower($data[$i]['name'])) . '123',
                'email_verified_at' => now(),
                'user_type'         => 'employee',
            ]);
            $user->givePermissionTo('login karyawan');
            $data[$i]['user_id'] = $user->id;
            $karyawan = Karyawan::create([
                'user_id'               => $user->id,
                'name'                  => $data[$i]['name'],
                'npk'                   => $data[$i]['npk'],
                'job_title_id'          => $data[$i]['job_title_id'],
                'branch_id'             => $data[$i]['branch_id'],
                'start_date'            => $data[$i]['start_date'],
                'bank_account_number'   => $data[$i]['bank_account_number'],
            ]);
            if ($karyawan) {
                DetailDiri::create($data[$i]['detail_diri']);
                DetailGaji::insert($data[$i]['detail_gaji']);
            }
        }
    }
}
