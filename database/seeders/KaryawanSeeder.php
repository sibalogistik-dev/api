<?php

namespace Database\Seeders;

use App\Models\{Agama, Cabang, DetailDiri, DetailGaji, Karyawan, User, Jabatan, MarriageStatus, Pendidikan};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $salary_type = ['monthly', 'daily'];

        $jobTitleIds = Jabatan::where('id', '!=', 1)->pluck('id')->toArray();
        if (empty($jobTitleIds)) {
            $jobTitleIds = [1];
        }

        $branchIds          = Cabang::pluck('id')->toArray();
        $religionIds        = Agama::pluck('id')->toArray();
        $educationIds       = Pendidikan::pluck('id')->toArray();
        $marriageStatusIds  = MarriageStatus::pluck('id')->toArray();
        $managerIds         = Karyawan::pluck('id')->toArray();
        if (empty($managerIds)) {
            $managerIds = [1, 2, 3];
        }

        $areaIds = [2101, 2102, 2103, 2104, 2105, 2171, 2172];

        $data = [
            [
                'name'                  => 'Galuh Mayang Sari',
                'npk'                   => '199706020015',
                'branch_id'             => 1,
                'job_title_id'          => 4,
                'manager_id'            => null,
                'start_date'            => '2020-06-15',
                'bank_account_number'   => '017401089343507',
                'detail_diri'           => [
                    'employee_id'           => 3,
                    'gender'                => 'perempuan',
                    'religion_id'           => 1,
                    'phone_number'          => '082383311213',
                    'place_of_birth_id'     => 2172,
                    'date_of_birth'         => '1997-06-02',
                    'address'               => 'Kampung Baru (Dabo Singkep)',
                    'blood_type'            => 'a',
                    'education_id'          => 5,
                    'marriage_status_id'    => 1,
                    'residential_area_id'   => 2172,
                ],
                'detail_gaji'           => [
                    [
                        'employee_id'           => 3,
                        'salary_type'           => 'monthly',
                        'monthly_base_salary'   => 5200000,
                        'daily_base_salary'     => (int) 5200000 / 26,
                        'meal_allowance'        => 15000,
                        'bonus'                 => 20000,
                        'allowance'             => 60000,
                        'overtime'              => 160,
                    ],
                ],
            ],
        ];

        for ($i = 0; $i < 4; $i++) {
            $name               = $faker->firstName() . ' ' . $faker->lastName();
            $birthDate          = $faker->dateTimeBetween('-40 years', '-20 years')->format('Y-m-d');
            $gender             = $faker->randomElement(['laki-laki', 'perempuan']);
            $monthlyBaseSalary  = $faker->randomElement([2860000, 3120000, 3380000, 3640000, 3900000]);
            $dailyBaseSalary    = round($monthlyBaseSalary / 26);

            $data[] = [
                'name'                  => $name,
                'npk'                   => $faker->numerify('##########') . $faker->unique()->randomNumber(2),
                'branch_id'             => $faker->randomElement($branchIds),
                'job_title_id'          => $faker->randomElement($jobTitleIds),
                'manager_id'            => $faker->randomElement($managerIds),
                'start_date'            => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'bank_account_number'   => $faker->numerify('0174011########'),
                'detail_diri'           => [
                    'employee_id'           => null,
                    'gender'                => $gender,
                    'religion_id'           => $faker->randomElement($religionIds),
                    'phone_number'          => $faker->numerify('08##########'),
                    'place_of_birth_id'     => $faker->randomElement($areaIds),
                    'date_of_birth'         => $birthDate,
                    'address'               => $faker->address(),
                    'blood_type'            => $faker->randomElement(['a', 'b', 'o', 'ab']),
                    'education_id'          => $faker->randomElement($educationIds),
                    'marriage_status_id'    => $faker->randomElement($marriageStatusIds),
                    'residential_area_id'   => $faker->randomElement($areaIds),
                ],
                'detail_gaji'           => [
                    [
                        'employee_id'           => null,
                        'salary_type'           => $salary_type[rand(0, 100) < 45 ? 0 : 1],
                        'monthly_base_salary'   => $monthlyBaseSalary,
                        'daily_base_salary'     => $dailyBaseSalary,
                        'meal_allowance'        => $faker->randomElement([15000, 20000, 25000]),
                        'bonus'                 => $faker->randomElement([0, 50000, 100000]),
                        'allowance'             => $faker->randomElement([20000, 40000, 60000, 80000, 100000]),
                        'overtime'              => 160,
                    ]
                ],
            ];
        }

        foreach ($data as $karyawanData) {
            $userName = str_replace(' ', '', strtolower($karyawanData['name']));

            $user = User::create([
                'name'              => $karyawanData['name'],
                'email'             => $userName . $faker->unique()->randomNumber(4) . '@' . $faker->safeEmailDomain(),
                'username'          => $userName . $faker->unique()->randomNumber(3),
                'password'          => bcrypt($userName . '123'),
                'email_verified_at' => now(),
                'user_type'         => 'employee',
            ]);

            $user->assignRole('Karyawan');

            $karyawan = Karyawan::create([
                'user_id'               => $user->id,
                'name'                  => $karyawanData['name'],
                'npk'                   => $karyawanData['npk'],
                'job_title_id'          => $karyawanData['job_title_id'],
                'manager_id'            => $karyawanData['manager_id'],
                'branch_id'             => $karyawanData['branch_id'],
                'start_date'            => $karyawanData['start_date'],
                'bank_account_number'   => $karyawanData['bank_account_number'],
            ]);

            if ($karyawan) {
                $detailDiriData                 = $karyawanData['detail_diri'];
                $detailDiriData['employee_id']  = $karyawan->id;
                DetailDiri::create($detailDiriData);

                foreach ($karyawanData['detail_gaji'] as $detailGajiItem) {
                    $detailGajiItem['employee_id'] = $karyawan->id;
                    DetailGaji::create($detailGajiItem);
                }
            }
        }
    }
}
