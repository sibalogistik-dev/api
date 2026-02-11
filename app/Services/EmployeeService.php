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
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

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
            $user->givePermissionTo('employee');
            $karyawanData = [
                'user_id'             => $user->id,
                'name'                => $data['name'],
                'bank_account_number' => $data['bank_account_number'],
                'npk'                 => $data['npk'],
                'job_title_id'        => $data['job_title_id'],
                'manager_id'          => $data['manager_id'] ?? null,
                'branch_id'           => $data['branch_id'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'] ?? null,
            ];
            if (!empty($data['contract'])) {
                $filePaths['contract'] = $this->storeFile($data['contract'], 'uploads/kontrak', $data['name']);
                $karyawanData['contract'] = $filePaths['contract'];
            }
            $karyawan = Karyawan::create($karyawanData);
            if (!empty($data['id_card_photo'])) {
                $filePaths['id_card_photo'] = $this->storeFile($data['id_card_photo'], 'uploads/ktp_foto', $data['name']);
            }
            if (!empty($data['passport_photo'])) {
                $filePaths['passport_photo'] = $this->storeFile($data['passport_photo'], 'uploads/pas_foto', $data['name']);
            }
            if (!empty($data['drivers_license_photo'])) {
                $filePaths['drivers_license_photo'] = $this->storeFile($data['drivers_license_photo'], 'uploads/sim_foto', $data['name']);
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
                'passport_photo'        => $filePaths['passport_photo'] ?? 'uploads/pas_foto/default.webp',
                'id_card_photo'         => $filePaths['id_card_photo'] ?? 'uploads/ktp_foto/default.webp',
                'drivers_license_photo' => $filePaths['drivers_license_photo'] ?? 'uploads/sim_foto/default.webp',
            ]);
            DetailGaji::create([
                'employee_id'           => $karyawan->id,
                'monthly_base_salary'   => $data['monthly_base_salary'],
                'daily_base_salary'     => $data['daily_base_salary'],
                'meal_allowance'        => $data['meal_allowance'],
                'bonus'                 => $data['bonus'],
                'allowance'             => $data['allowance'],
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
            if (isset($data['karyawan']) && count($data['karyawan']) > 0) {
                $dataKaryawan = $data['karyawan'];
                if (isset($dataKaryawan['contract']) && !empty($dataKaryawan['contract'])) {
                    $oldFilePaths[]             = $karyawan->contract;
                    $newFilePaths['contract']   = $this->storeFile($dataKaryawan['contract'], 'uploads/kontrak', $karyawan->name);
                    $dataKaryawan['contract']   = $newFilePaths['contract'];
                }
                $karyawan->update($dataKaryawan);
            }
            if (isset($data['detail_diri']) && count($data['detail_diri']) > 0) {
                $details        = $karyawan->employeeDetails;
                $dataDetailDiri = $data['detail_diri'];
                if (!empty($dataDetailDiri['passport_photo']) && isset($dataDetailDiri['passport_photo']) && $details) {
                    $oldFilePaths[]                             = $details->passport_photo;
                    $newFilePaths['passport_photo']             = $this->storeFile($dataDetailDiri['passport_photo'], 'uploads/pas_foto', $karyawan->name);
                    $dataDetailDiri['passport_photo']           = $newFilePaths['passport_photo'];
                }
                if (!empty($dataDetailDiri['id_card_photo']) && isset($dataDetailDiri['id_card_photo']) && $details) {
                    $oldFilePaths[]                             = $details->id_card_photo;
                    $newFilePaths['id_card_photo']              = $this->storeFile($dataDetailDiri['id_card_photo'], 'uploads/ktp_foto', $karyawan->name);
                    $dataDetailDiri['id_card_photo']            = $newFilePaths['id_card_photo'];
                }
                if (!empty($dataDetailDiri['drivers_license_photo']) && isset($dataDetailDiri['drivers_license_photo']) && $details) {
                    $oldFilePaths[]                             = $details->drivers_license_photo;
                    $newFilePaths['drivers_license_photo']      = $this->storeFile($dataDetailDiri['drivers_license_photo'], 'uploads/sim_foto', $karyawan->name);
                    $dataDetailDiri['drivers_license_photo']    = $newFilePaths['drivers_license_photo'];
                }
                if ($details) {
                    $details->update($dataDetailDiri);
                } else {
                    $dataDetailDiri['employee_id'] = $karyawan->id;
                    DetailDiri::create($dataDetailDiri);
                }
            }
            if (isset($data['detail_gaji']) && count($data['detail_gaji']) > 0) {
                $dataDetailGaji = $data['detail_gaji'];
                $salaryDetails  = $karyawan->salaryDetails;
                if ($salaryDetails) {
                    $salaryDetails->update($dataDetailGaji);
                } else {
                    $dataDetailGaji['employee_id'] = $karyawan->id;
                    DetailGaji::create($dataDetailGaji);
                }
            }

            DB::commit();
            return $karyawan;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Failed to update employee data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path, string $employeeName, int $quality = 90)
    {
        $saneName = Str::slug($employeeName);
        $isImage = Str::startsWith($file->getMimeType(), 'image/');

        if ($isImage) {
            $filename = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.webp';
            $fullPath = $path . '/' . $filename;

            $imageContent = Image::read($file->getRealPath())->toWebp($quality);
            Storage::disk('public')->put($fullPath, (string) $imageContent);

            return $fullPath;
        } else {
            $extension = $file->getClientOriginalExtension();
            $filename  = date('Ymd-His') . '-' . $saneName . '-' . Str::random(10) . '.' . $extension;
            $fullPath  = $path . '/' . $filename;

            Storage::disk('public')->putFileAs($path, $file, $filename);

            return $fullPath;
        }
    }
}
