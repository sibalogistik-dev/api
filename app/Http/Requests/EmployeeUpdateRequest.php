<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $karyawanId = $this->route('employee');

        return [
            'karyawan'                          => ['sometimes', 'array'],
            'karyawan.name'                     => ['sometimes', 'string', 'max:255'],
            'karyawan.npk'                      => ['sometimes', 'string', 'max:50', Rule::unique('karyawans', 'npk')->ignore($karyawanId)],
            'karyawan.job_title_id'             => ['sometimes', 'integer', 'exists:jabatans,id'],
            'karyawan.manager_id'               => ['sometimes', 'integer', 'exists:karyawans,id'],
            'karyawan.branch_id'                => ['sometimes', 'integer', 'exists:cabangs,id'],
            'karyawan.start_date'               => ['sometimes', 'date'],
            'karyawan.end_date'                 => ['sometimes', 'date'],
            'karyawan.contract'                 => ['sometimes', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'karyawan.bank_account_number'      => ['sometimes', 'string', 'max:50', Rule::unique('karyawans', 'bank_account_number')->ignore($karyawanId)],

            'detail_diri'                       => ['sometimes', 'array'],
            'detail_diri.gender'                => ['sometimes', 'in:laki-laki,perempuan'],
            'detail_diri.religion_id'           => ['sometimes', 'integer', 'exists:agamas,id'],
            'detail_diri.phone_number'          => ['sometimes', 'string', 'max:20'],
            'detail_diri.place_of_birth_id'     => ['sometimes', 'integer', 'exists:cities,code'],
            'detail_diri.date_of_birth'         => ['sometimes', 'date'],
            'detail_diri.address'               => ['sometimes', 'string'],
            'detail_diri.blood_type'            => ['sometimes', 'string', 'in:a,b,ab,o,none'],
            'detail_diri.education_id'          => ['sometimes', 'integer', 'exists:pendidikans,id'],
            'detail_diri.marriage_status_id'    => ['sometimes', 'integer', 'exists:marriage_statuses,id'],
            'detail_diri.residential_area_id'   => ['sometimes', 'integer', 'exists:cities,code'],
            'detail_diri.passport_photo'        => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'detail_diri.id_card_photo'         => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'detail_diri.drivers_license_photo' => ['sometimes', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'detail_gaji'                       => ['sometimes', 'array'],
            'detail_gaji.salary_type'           => ['sometimes', 'string', 'in:monthly,daily'],
            'detail_gaji.monthly_base_salary'   => ['sometimes', 'numeric', 'min:0'],
            'detail_gaji.daily_base_salary'     => ['sometimes', 'numeric', 'min:0'],
            'detail_gaji.meal_allowance'        => ['sometimes', 'numeric', 'min:0'],
            'detail_gaji.bonus'                 => ['sometimes', 'numeric', 'min:0'],
            'detail_gaji.allowance'             => ['sometimes', 'numeric', 'min:0'],
            'detail_gaji.overtime'              => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422)
        );
    }
}
