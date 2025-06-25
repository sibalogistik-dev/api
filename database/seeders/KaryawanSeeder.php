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
                'user_id' => null,
                'nama' => 'Mohammad Farid Hasymi',
                'npk' => '199708170073',
                'cabang_id' => 1,
                'jabatan_id' => 3,
                'daerah_tinggal_id' => 2172,
                'tanggal_masuk' => '2025-04-14',
                'detail_diri' => [
                    'karyawan_id' => null,
                    'jenis_kelamin' => 'laki-laki',
                    'agama_id' => 1,
                    'no_telp' => '082283784873',
                    'tempat_lahir_id' => 2172,
                    'tanggal_lahir' => '1997-08-17',
                    'alamat' => 'Jl. Kota Piring',
                    'golongan_darah' => 'ab',
                    'pendidikan_id' => 5,
                    'status_kawin' => 'belum kawin',
                    'daerah_tinggal_id' => 2172,
                ],
                'detail_gaji' => [
                    'karyawan_id' => null,
                    'no_rekening' => '017401106946503',
                    'status_gaji' => 'harian',
                    'gaji_harian' => 96153,
                    'gaji_bulanan' => 0,
                    'uang_makan' => 0,
                    'bonus' => 0,
                    'tunjangan' => 0,
                ],
            ],
            [
                'user_id' => null,
                'nama' => 'Toby Fiski',
                'npk' => '199905120010',
                'cabang_id' => 2,
                'jabatan_id' => 39,
                'daerah_tinggal_id' => 2172,
                'tanggal_masuk' => '2018-07-01',
                'detail_diri' => [
                    'karyawan_id' => null,
                    'jenis_kelamin' => 'laki-laki',
                    'agama_id' => 1,
                    'no_telp' => '082387301492',
                    'tempat_lahir_id' => 2172,
                    'tanggal_lahir' => '1999-05-12',
                    'alamat' => 'JL. Karya Perum. Griya Pinang Asri (Tanjungpinang)',
                    'golongan_darah' => 'a',
                    'pendidikan_id' => 4,
                    'status_kawin' => 'kawin',
                    'daerah_tinggal_id' => 2172,
                    'pas_foto' => 'uploads/pas_foto/6kSUARDLWXsGr6MNeYkP8Qc4WOQFbTCUKu7ivT6p.jpg',
                    'ktp_foto' => 'uploads/ktp_foto/Tk6hm8EGQVmRK04Gp6ERgbDrUfxIMD63CapTCZ5E.jpg',
                    'sim_foto' => 'uploads/sim_foto/Tk6hm8EGQVmRK04Gp6ERgbDrUfxIMD63CapTCZ5E.jpg',
                ],
                'detail_gaji' => [
                    'karyawan_id' => null,
                    'no_rekening' => '017401089349503',
                    'status_gaji' => 'harian',
                    'gaji_harian' => 50000,
                    'gaji_bulanan' => 4050000,
                    'uang_makan' => 15000,
                    'bonus' => 20000,
                    'tunjangan' => 70769,
                ],
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
            $data[$i]['user_id'] = $user->id;
            $karyawan = Karyawan::create([
                'user_id' => $user->id,
                'nama' => $data[$i]['nama'],
                'npk' => $data[$i]['npk'],
                'jabatan_id' => $data[$i]['jabatan_id'],
                'cabang_id' => $data[$i]['cabang_id'],
                'tanggal_masuk' => $data[$i]['tanggal_masuk'],
            ]);
            if ($karyawan) {
                $idkry = $karyawan->id;
                $data[$i]['detail_diri']['karyawan_id'] = $idkry;
                DetailDiri::create($data[$i]['detail_diri']);
                $data[$i]['detail_gaji']['karyawan_id'] = $idkry;
                DetailGaji::create($data[$i]['detail_gaji']);
            }
        }
    }
}
