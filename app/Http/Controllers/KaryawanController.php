<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\DetailDiri;
use App\Models\DetailGaji;
use App\Models\Karyawan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $keyword = $request->input('q');
        $cabang = $request->input('cabang');

        $query = Karyawan::with([
            'jabatan',
            'cabang',
            'detail_diri.agama',
            'detail_diri.tempat_lahir',
            'detail_diri.pendidikan',
            'detail_diri.daerah_tinggal',
            'detail_gaji',
            'histori_gaji',
        ]);

        $query->when($keyword, function ($q, $keyword) {
            return $q->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%")
                    ->orWhere('npk', 'like', "%{$keyword}%")
                    ->orWhereHas('jabatan', function ($jabatanQuery) use ($keyword) {
                        $jabatanQuery->where('name', 'like', "%{$keyword}%");
                    });
            });
        });

        $query->when($cabang && $cabang !== 'semua', function ($q, $cabang) {
            return $q->where('cabang_id', $cabang);
        });

        $query->orderBy('id', 'asc');

        if ($request->boolean('paginate')) {
            $karyawan = $query->paginate($perPage)->withQueryString();
        } else {
            $karyawan = $query->get();
        }

        return ApiResponseHelper::success('Daftar Karyawan', $karyawan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Akun
            'username'              => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8'],
            // Data Pegawai
            'name'                  => ['required', 'string', 'max:255'],
            'npk'                   => ['required', 'string', 'max:50', 'unique:karyawans,npk'],
            'jabatan_id'            => ['required', 'integer', 'exists:jabatans,id'],
            'cabang_id'             => ['required', 'integer', 'exists:cabangs,id'],
            'start_date'            => ['required', 'date'],
            'end_date'              => ['nullable', 'date'],
            'contract'              => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'bank_account_number'   => ['required', 'string', 'max:50', 'unique:karyawans,bank_account_number'],
            // Data Diri
            'jenis_kelamin'         => ['required', 'in:laki-laki,perempuan'],
            'agama_id'              => ['required', 'integer', 'exists:agamas,id'],
            'no_telp'               => ['required', 'string', 'max:20'],
            'tempat_lahir_id'       => ['required', 'integer', 'exists:indonesia_cities,code'],
            'tanggal_lahir'         => ['required', 'date'],
            'alamat'                => ['required', 'string'],
            'golongan_darah'        => ['nullable', 'in:a,b,ab,o,none'],
            'pendidikan_id'         => ['required', 'integer', 'exists:pendidikans,id'],
            'status_kawin'          => ['required', 'in:belum kawin,kawin,duda,janda'],
            'daerah_tinggal_id'     => ['required', 'integer', 'exists:indonesia_cities,code'],
            // gambar
            'pas_foto'              => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'ktp_foto'              => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'sim_foto'              => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            // Gaji
            'monthly_base_salary'   => ['required', 'numeric', 'min:0'],
            'daily_base_salary'     => ['required', 'numeric', 'min:0'],
            'meal_allowance'        => ['required', 'numeric', 'min:0'],
            'bonus'                 => ['required', 'numeric', 'min:0'],
            'allowance'             => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'username'          => $request->username,
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'email_verified_at' => now(),
                'user_type'         => 'employee',
            ]);

            $user->givePermissionTo('karyawan app');

            $karyawan = Karyawan::create([
                'user_id'       => $user->id,
                'name'          => $request->name,
                'npk'           => $request->npk,
                'jabatan_id'    => $request->jabatan_id,
                'cabang_id'     => $request->cabang_id,
                'start_date'    => $request->tanggal_masuk,
            ]);

            $pasFotoPath = $request->file('pas_foto')->store('uploads/pas_foto', 'public');
            $ktpFotoPath = $request->file('ktp_foto')->store('uploads/ktp_foto', 'public');
            $simFotoPath = $request->file('sim_foto') ? $request->file('sim_foto')->store('uploads/sim_foto', 'public') : null;

            DetailDiri::create([
                'karyawan_id'       => $karyawan->id,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'agama_id'          => $request->agama_id,
                'no_telp'           => $request->no_telp,
                'tempat_lahir_id'   => $request->tempat_lahir_id,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'alamat'            => $request->alamat,
                'golongan_darah'    => $request->golongan_darah,
                'pendidikan_id'     => $request->pendidikan_id,
                'status_kawin'      => $request->status_kawin,
                'daerah_tinggal_id' => $request->daerah_tinggal_id,
                'pas_foto'          => $pasFotoPath,
                'ktp_foto'          => $ktpFotoPath,
                'sim_foto'          => $simFotoPath,
            ]);

            DetailGaji::create([
                'karyawan_id'           => $karyawan->id,
                'daily_base_salary'     => $request->daily_base_salary,
                'monthly_base_salary'   => $request->monthly_base_salary,
                'meal_allowance'        => $request->meal_allowance,
                'bonus'                 => $request->bonus,
                'allowance'             => $request->allowance,
            ]);

            DB::commit();
            return ApiResponseHelper::success('Data karyawan berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            // Hapus file yang mungkin sudah terunggah
            if (isset($pasFotoPath)) {
                Storage::disk('public')->delete($pasFotoPath);
            }
            if (isset($ktpFotoPath)) {
                Storage::disk('public')->delete($ktpFotoPath);
            }
            if (isset($simFotoPath)) {
                Storage::disk('public')->delete($simFotoPath);
            }
            return ApiResponseHelper::error('Terjadi kesalahan saat menyimpan data', $e->getMessage(), 500);
        }
    }

    public function show(Karyawan $karyawan)
    {
        $data = Karyawan::with('detail_diri', 'detail_gaji')
            ->withTrashed()
            ->find($karyawan->id);
        return ApiResponseHelper::success('Detail Data karyawan', $data);
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => ['required', 'string', 'max:255'],
            'npk'                   => ['required', 'string', 'max:50', 'unique:karyawans,npk,' . $karyawan->id],
            'jabatan_id'            => ['required', 'integer', 'exists:jabatans,id'],
            'cabang_id'             => ['required', 'integer', 'exists:cabangs,id'],
            'start_date'            => ['required', 'date'],
            'jenis_kelamin'         => ['required', 'in:laki-laki,perempuan'],
            'agama_id'              => ['required', 'integer', 'exists:agamas,id'],
            'no_telp'               => ['required', 'string', 'max:20'],
            'tempat_lahir_id'       => ['required', 'integer', 'exists:indonesia_cities,code'],
            'tanggal_lahir'         => ['required', 'date'],
            'alamat'                => ['required', 'string'],
            'golongan_darah'        => ['nullable', 'in:a,b,ab,o,none'],
            'pendidikan_id'         => ['required', 'integer', 'exists:pendidikans,id'],
            'status_kawin'          => ['required', 'in:belum kawin,kawin,duda,janda'],
            'daerah_tinggal_id'     => ['required', 'integer', 'exists:indonesia_cities,code'],
            'pas_foto'              => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'ktp_foto'              => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'sim_foto'              => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'daily_base_salary'     => ['required', 'numeric', 'min:0'],
            'monthly_base_salary'   => ['required', 'numeric', 'min:0'],
            'meal_allowance'        => ['required', 'numeric', 'min:0'],
            'bonus'                 => ['required', 'numeric', 'min:0'],
            'allowance'             => ['required', 'numeric', 'min:0'],
        ]);
        if ($validator->fails()) {
            logger()->info($validator->errors());
            return ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422);
        }
        DB::beginTransaction();
        try {
            $karyawan->update([
                'name' => $request->name,
                'npk' => $request->npk,
                'jabatan_id' => $request->jabatan_id,
                'cabang_id' => $request->cabang_id,
                'start_date' => $request->start_date,
            ]);
            if ($karyawan->user) {
                $karyawan->user->update(['name' => $request->name]);
            }
            $detailDiriData = $request->only([
                'jenis_kelamin',
                'agama_id',
                'no_telp',
                'tempat_lahir_id',
                'tanggal_lahir',
                'alamat',
                'golongan_darah',
                'pendidikan_id',
                'status_kawin',
                'daerah_tinggal_id',
            ]);
            if ($request->hasFile('pas_foto')) {
                $detailDiriData['pas_foto'] = $request->file('pas_foto')->store('uploads/pas_foto', 'public');
            }
            if ($request->hasFile('ktp_foto')) {
                $detailDiriData['ktp_foto'] = $request->file('ktp_foto')->store('uploads/ktp_foto', 'public');
            }
            if ($request->hasFile('sim_foto')) {
                $detailDiriData['sim_foto'] = $request->file('sim_foto')->store('uploads/sim_foto', 'public');
            }
            $karyawan->detail_diri()
                ->update($detailDiriData);
            $karyawan->detail_gaji()
                ->update($request->only([
                    'daily_base_salary',
                    'monthly_base_salary',
                    'meal_allowance',
                    'bonus',
                    'allowance'
                ]));
            DB::commit();
            return ApiResponseHelper::success('Data karyawan berhasil diubah');
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::error('Terjadi kesalahan saat menyimpan data', $e->getMessage(), 500);
        }
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->user->delete();
        $delete = $karyawan->delete();
        if ($delete) {
            return ApiResponseHelper::success('Data karyawan berhasil dinon-aktifkan');
        } else {
            return ApiResponseHelper::error('Data karyawan gagal dinon-aktifkan');
        }
    }
}
