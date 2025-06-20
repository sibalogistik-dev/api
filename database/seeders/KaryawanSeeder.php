<?php

namespace Database\Seeders;

use App\Models\{DetailDiri, DetailKaryawan, Karyawan, User};
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
                'user_id' => null,
                'nama' => 'Mohammad Farid Hasymi',
                'jenis_kelamin' => 'laki-laki',
                'agama_id' => 1,
                'no_telp' => '082283784873',
                'tempat_lahir_id' => 2172,
                'tanggal_lahir' => '1997-08-17',
                'alamat' => 'Jl. Kota Piring',
                'golongan_darah' => 'ab',
                'pendidikan_id' => 5,
                'status_kawin' => 'belum kawin',
                'detail_diri' => [
                    'karyawan_id' => null,
                    'pas_foto' => '-',
                    'ktp_foto' => '-',
                    'sim_foto' => '-',
                ],
                'detail_karyawan' => [
                    'karyawan_id' => null,
                    'nik' => '1997081700073',
                    'cabang_id' => 1,
                    'jabatan_id' => 3,
                    'daerah_tinggal_id' => 2172,
                    'tanggal_masuk' => date('Y-m-d'),
                ],
                'detail_gaji' => [
                    'karyawan_id' => null,
                    'no_rekening' => '017401106946503'
                ],
                'role' => [
                    'name' => 'it-staff'
                ]
            ],
            [
                'user_id' => null,
                'nama' => 'Toby Fiski',
                'jenis_kelamin' => 'laki-laki',
                'agama_id' => 1,
                'no_telp' => '082387301492',
                'tempat_lahir_id' => 2172,
                'tanggal_lahir' => '1999-05-12',
                'alamat' => 'JL. Karya Perum. Griya Pinang Asri (Tanjungpinang)',
                'golongan_darah' => 'a',
                'pendidikan_id' => 4,
                'status_kawin' => 'kawin',
                'detail_diri' => [
                    'karyawan_id' => null,
                    'pas_foto' => '-',
                    'ktp_foto' => '-',
                    'sim_foto' => '-',
                ],
                'detail_karyawan' => [
                    'karyawan_id' => null,
                    'nik' => '199905120010',
                    'cabang_id' => 2,
                    'jabatan_id' => 39,
                    'daerah_tinggal_id' => 2172,
                    'tanggal_masuk' => date('Y-m-d'),
                ],
                'detail_gaji' => [
                    'karyawan_id' => null,
                    'no_rekening' => '017401089349503'
                ],
                'role' => [
                    'name' => 'operasional-staff'
                ]
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
            $user->givePermissionTo('absensi app');
            $user->assignRole($data[$i]['role']['name']);
            $data[$i]['user_id'] = $user->id;
            $karyawan = Karyawan::create([
                'user_id' => $user->id,
                'nama' => $data[$i]['nama'],
                'jenis_kelamin' => $data[$i]['jenis_kelamin'],
                'agama_id' => $data[$i]['agama_id'],
                'no_telp' => $data[$i]['no_telp'],
                'tempat_lahir_id' => $data[$i]['tempat_lahir_id'],
                'tanggal_lahir' => $data[$i]['tanggal_lahir'],
                'alamat' => $data[$i]['alamat'],
                'golongan_darah' => $data[$i]['golongan_darah'],
                'pendidikan_id' => $data[$i]['pendidikan_id'],
                'status_kawin' => $data[$i]['status_kawin'],
            ]);
            if ($karyawan) {
                $idkry = $karyawan->id;
                // detail diri
                $data[$i]['detail_diri']['karyawan_id'] = $idkry;
                DetailDiri::create($data[$i]['detail_diri']);
                // detail karyawan
                $data[$i]['detail_karyawan']['karyawan_id'] = $idkry;
                DetailKaryawan::create($data[$i]['detail_karyawan']);
                // detail gaji
            }
        }
    }
}
