<?php

namespace App\Services;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\DetailDiri;
use App\Models\DetailGaji;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class EmployeeService
{
    public function create(array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            $user = User::create([
                'username'          => $data['username'],
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => $data['password'],
                'email_verified_at' => now(),
                'user_type'         => 'employee',
            ]);
            $user->givePermissionTo('app.access.karyawan');
            $karyawanData = [
                'user_id'             => $user->id,
                'name'                => $data['name'],
                'bank_account_number' => $data['bank_account_number'],
                'npk'                 => $data['npk'],
                'job_title_id'        => $data['job_title_id'],
                'manager_id'          => $data['manager_id'] ?? null,
                'branch_id'           => $data['branch_id'],
                'start_date'          => $data['start_date'],
            ];
            if (!empty($data['contract'])) {
                $filePaths['contract'] = $this->storeFile($data['contract'], 'uploads/kontrak');
                $karyawanData['contract'] = $filePaths['contract'];
            }
            $karyawan = Karyawan::create($karyawanData);
            $filePaths['passport_photo'] = $this->storeFile($data['passport_photo'], 'uploads/pas_foto');
            $filePaths['id_card_photo'] = $this->storeFile($data['id_card_photo'], 'uploads/ktp_foto');
            if (!empty($data['drivers_license_photo'])) {
                $filePaths['drivers_license_photo'] = $this->storeFile($data['drivers_license_photo'], 'uploads/sim_foto');
            }
            DetailDiri::create([
                'employee_id'           => $karyawan->id,
                'gender'                => $data['gender'],
                'religion_id'           => $data['religion_id'],
                'phone_number'          => $data['phone_number'],
                'place_of_birth_id'     => $data['place_of_birth_id'],
                'date_of_birth'         => $data['date_of_birth'],
                'address'               => $data['address'],
                'blood_type'            => $data['blood_type'],
                'education_id'          => $data['education_id'],
                'marriage_status_id'    => $data['marriage_status_id'],
                'residential_area_id'   => $data['residential_area_id'],
                'passport_photo'        => $filePaths['passport_photo'] ?? null,
                'id_card_photo'         => $filePaths['id_card_photo'] ?? null,
                'drivers_license_photo' => $filePaths['drivers_license_photo'] ?? null,
            ]);
            DetailGaji::create([
                'employee_id'         => $karyawan->id,
                'monthly_base_salary' => $data['monthly_base_salary'],
                'daily_base_salary'   => $data['daily_base_salary'],
                'meal_allowance'      => $data['meal_allowance'],
                'bonus'               => $data['bonus'],
                'allowance'           => $data['allowance'],
            ]);

            DB::commit();
            return $karyawan;
        } catch (Exception $e) {
            DB::rollback();
            foreach ($filePaths as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            throw new Exception('Failed to save employee data: ' . $e->getMessage());
        }
    }

    public function update(Karyawan $karyawan, array $data)
    {
        $newFilePaths   = [];
        $oldFilePaths   = [];
        DB::beginTransaction();
        try {
            $karyawan->loadMissing('user');
            $karyawanData = [
                'name'                => $data['name'],
                'bank_account_number' => $data['bank_account_number'],
                'npk'                 => $data['npk'],
                'job_title_id'        => $data['job_title_id'],
                'branch_id'           => $data['branch_id'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'] ?? null,
            ];
            if (!empty($data['contract'])) {
                $oldFilePaths[]             = $karyawan->contract;
                $newFilePaths['contract']   = $this->storeFile($data['contract'], 'uploads/kontrak');
                $karyawanData['contract']   = $newFilePaths['contract'];
            }
            $karyawan->update($karyawanData);
            $karyawan->loadMissing('employeeDetails');
            $details    = $karyawan->employeeDetails;
            $detailDiriData = [
                'gender'                => $data['gender'],
                'religion_id'           => $data['religion_id'],
                'phone_number'          => $data['phone_number'],
                'place_of_birth_id'     => $data['place_of_birth_id'],
                'date_of_birth'         => $data['date_of_birth'],
                'address'               => $data['address'],
                'blood_type'            => $data['blood_type'],
                'education_id'          => $data['education_id'],
                'marriage_status_id'    => $data['marriage_status_id'],
                'residential_area_id'   => $data['residential_area_id'],
            ];
            if (!empty($data['passport_photo']) && $details) {
                $oldFilePaths[]                     = $details->passport_photo;
                $newFilePaths['passport_photo']     = $this->storeFile($data['passport_photo'], 'uploads/pas_foto');
                $detailDiriData['passport_photo']   = $newFilePaths['passport_photo'];
            }
            if (!empty($data['id_card_photo']) && $details) {
                $oldFilePaths[]                     = $details->id_card_photo;
                $newFilePaths['id_card_photo']      = $this->storeFile($data['id_card_photo'], 'uploads/ktp_foto');
                $detailDiriData['id_card_photo']    = $newFilePaths['id_card_photo'];
            }
            if (!empty($data['drivers_license_photo']) && $details) {
                $oldFilePaths[]                             = $details->drivers_license_photo;
                $newFilePaths['drivers_license_photo']      = $this->storeFile($data['drivers_license_photo'], 'uploads/sim_foto');
                $detailDiriData['drivers_license_photo']    = $newFilePaths['drivers_license_photo'];
            }
            if ($details) {
                $details->update($detailDiriData);
            } else {
                $detailDiriData['employee_id'] = $karyawan->id;
                DetailDiri::create($detailDiriData);
            }
            $karyawan->loadMissing('salaryDetails');
            $detailGajiData = [
                'monthly_base_salary' => $data['monthly_base_salary'],
                'daily_base_salary'   => $data['daily_base_salary'],
                'meal_allowance'      => $data['meal_allowance'],
                'bonus'               => $data['bonus'],
                'allowance'           => $data['allowance'],
            ];
            if ($karyawan->salaryDetails) {
                $karyawan->salaryDetails->update($detailGajiData);
            } else {
                $detailGajiData['employee_id'] = $karyawan->id;
                DetailGaji::create($detailGajiData);
            }
            DB::commit();
            foreach ($oldFilePaths as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            return $karyawan;
        } catch (Exception $e) {
            DB::rollback();
            foreach ($newFilePaths as $path) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }
            }
            throw new Exception('Failed to update employee data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path): string
    {
        return $file->store($path, 'public');
    }
}
