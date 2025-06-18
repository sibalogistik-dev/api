<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
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
                'nama' => 'Mohammad Farid Hasymi',
                'cabang_id' => 1,
                'user_id' => null,
                'jabatan_id' => 3,
                'nik' => '1997081700073',
                'no_telepon' => '082283784873',
                'alamat' => 'Jl. Kota Piring',
            ],
            [
                'nama' => 'Toby Fiski',
                'cabang_id' => 2,
                'user_id' => null,
                'jabatan_id' => 39,
                'nik' => '199905120010',
                'no_telepon' => '082387301492',
                'alamat' => 'JL. Karya Perum. Griya Pinang Asri (Tanjungpinang)',
            ],
        ];

        for ($i = 0; $i < count($data); $i++) {
            $user = User::create([
                'name' => $data[$i]['nama'],
                'email' => str_replace(' ', '', strtolower($data[$i]['nama'])) . '@gmail.com',
                'username' => str_replace(' ', '', strtolower($data[$i]['nama'])),
                'password' => str_replace(' ', '', strtolower($data[$i]['nama'])) . '123',
                'email_verified_at' => now(),
                'user_type' => 'employee',
            ]);
            $user->assignRole('it-staff');
            $data[$i]['user_id'] = $user->id;
            Karyawan::create($data[$i]);
        }
    }
}
