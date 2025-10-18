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

        $query = Karyawan::with(['jabatan', 'cabang.kota', 'detail_diri.agama', 'detail_diri.tempat_lahir', 'detail_diri.pendidikan', 'detail_diri.daerah_tinggal', 'detail_gaji', 'histori_gaji']);

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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'              => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8'],
            'name'                  => ['required', 'string', 'max:255'],
            'npk'                   => ['required', 'string', 'max:50', 'unique:karyawans,npk'],
            'job_title_id'          => ['required', 'integer', 'exists:jabatans,id'],
            'branch_id'             => ['required', 'integer', 'exists:cabangs,id'],
            'start_date'            => ['required', 'date'],
            'end_date'              => ['nullable', 'date'],
            'contract'              => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'bank_account_number'   => ['required', 'string', 'max:50', 'unique:karyawans,bank_account_number'],
            'gender'                => ['required', 'in:laki-laki,perempuan'],
            'religion_id'           => ['required', 'integer', 'exists:agamas,id'],
            'phone_number'          => ['required', 'string', 'max:20'],
            'place_of_birth_id'     => ['required', 'integer', 'exists:indonesia_cities,code'],
            'date_of_birth'         => ['required', 'date'],
            'address'               => ['required', 'string'],
            'blood_type'            => ['nullable', 'in:a,b,ab,o,none'],
            'education_id'          => ['required', 'integer', 'exists:pendidikans,id'],
            'marriage_status_id'    => ['required', 'integer', 'in:belum kawin,kawin,duda,janda'],
            'residential_area_id'   => ['required', 'integer', 'exists:indonesia_cities,code'],
            'passport_photo'        => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'id_card_photo'         => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'drivers_license_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
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
            $userData                       = $request->only(['username', 'name', 'email', 'password']);
            $userData['email_verified_at']  = now();
            $userData['user_type']          = 'employee';
            $user                           = User::create($userData);

            $user->givePermissionTo('karyawan app');

            $karyawanData               = $request->only(['name', 'npk', 'job_title_id', 'branch_id', 'start_date']);
            $karyawanData['user_id']    = $user->id;

            $karyawan = Karyawan::create($karyawanData);

            $pasFotoPath = $request->file('passport_photo')->store('uploads/pas_foto', 'public');
            $ktpFotoPath = $request->file('id_card_photo')->store('uploads/ktp_foto', 'public');
            $simFotoPath = $request->file('drivers_license_photo') ? $request->file('drivers_license_photo')->store('uploads/sim_foto', 'public') : null;

            $detailDiriData                             = $request->only(['gender', 'region_id', 'phone_number', 'place_of_birth_id', 'date_of_birth', 'address', 'blood_type', 'education_id', 'marriage_status_id', 'residential_area_id']);
            $detailDiriData['employee_id']              = $karyawan->id;
            $detailDiriData['passport_photo']           = $pasFotoPath;
            $detailDiriData['id_card_photo']            = $ktpFotoPath;
            $detailDiriData['drivers_license_photo']    = $simFotoPath;
            DetailDiri::create($detailDiriData);

            $detailGajiData                 = $request->only(['monthly_base_salary', 'daily_base_salary', 'meal_allowance', 'bonus', 'allowance']);
            $detailGajiData['employee_id']  = $karyawan->id;
            DetailGaji::create($detailGajiData);

            DB::commit();
            return ApiResponseHelper::success('Data karyawan berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
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
        $data = Karyawan::with([
            'employeeDetails.religion',
            'employeeDetails.birthPlace:code,name',
            'employeeDetails.education',
            'employeeDetails.residentialArea:code,name',
            'employeeDetails.marriageStatus',
            'jobTitle',
            'attendance.attendanceStatus',
        ])->withTrashed()->find($karyawan->id);
        return ApiResponseHelper::success('Detail Data karyawan', $data);
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => ['required', 'string', 'max:255'],
            'npk'                   => ['required', 'string', 'max:50', 'unique:karyawans,npk,' . $karyawan->id],
            'job_title_id'          => ['required', 'integer', 'exists:jabatans,id'],
            'branch_id'             => ['required', 'integer', 'exists:cabangs,id'],
            'start_date'            => ['required', 'date'],
            'gender'                => ['required', 'in:laki-laki,perempuan'],
            'religion_id'           => ['required', 'integer', 'exists:agamas,id'],
            'no_telp'               => ['required', 'string', 'max:20'],
            'place_of_birth'        => ['required', 'integer', 'exists:indonesia_cities,code'],
            'date_of_birth'         => ['required', 'date'],
            'address'               => ['required', 'string'],
            'blood_type'            => ['nullable', 'in:a,b,ab,o,none'],
            'education_id'          => ['required', 'integer', 'exists:pendidikans,id'],
            'marriage_status_id'    => ['required', 'in:belum kawin,kawin,duda,janda'],
            'residential_area_id'   => ['required', 'integer', 'exists:indonesia_cities,code'],
            'passport_photo'        => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'id_card_photo'         => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'drivers_license_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
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
                'job_title_id' => $request->job_title_id,
                'branch_id' => $request->branch_id,
                'start_date' => $request->start_date,
            ]);
            if ($karyawan->user) {
                $karyawan->user->update(['name' => $request->name]);
            }
            $detailDiriData = $request->only([
                'gender',
                'religion_id',
                'phone_number',
                'place_of_birth_id',
                'date_of_birth',
                'address',
                'blood_type',
                'education_id',
                'marriage_status',
                'residential_area_id',
            ]);
            if ($request->hasFile('passport_photo')) {
                $detailDiriData['passport_photo'] = $request->file('passport_photo')->store('uploads/pas_foto', 'public');
            }
            if ($request->hasFile('id_card_photo')) {
                $detailDiriData['id_card_photo'] = $request->file('id_card_photo')->store('uploads/ktp_foto', 'public');
            }
            if ($request->hasFile('drivers_license_photo')) {
                $detailDiriData['drivers_license_photo'] = $request->file('drivers_license_photo')->store('uploads/sim_foto', 'public');
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
