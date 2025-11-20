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
            'karyawan.name'                     => ['nullable', 'string', 'max:255'],
            'karyawan.npk'                      => ['nullable', 'string', 'max:50', Rule::unique('karyawans', 'npk')->ignore($karyawanId)],
            'karyawan.job_title_id'             => ['nullable', 'integer', 'exists:jabatans,id'],
            'karyawan.manager_id'               => ['nullable', 'integer', 'exists:karyawans,id'],
            'karyawan.branch_id'                => ['nullable', 'integer', 'exists:cabangs,id'],
            'karyawan.start_date'               => ['nullable', 'date'],
            'karyawan.end_date'                 => ['nullable', 'date'],
            'karyawan.contract'                 => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'karyawan.bank_account_number'      => ['nullable', 'string', 'max:50', Rule::unique('karyawans', 'bank_account_number')->ignore($karyawanId)],

            'detail_diri'                       => ['sometimes', 'array'],
            'detail_diri.gender'                => ['nullable', 'in:laki-laki,perempuan'],
            'detail_diri.religion_id'           => ['nullable', 'integer', 'exists:agamas,id'],
            'detail_diri.phone_number'          => ['nullable', 'string', 'max:20'],
            'detail_diri.place_of_birth_id'     => ['nullable', 'integer', 'exists:cities,code'],
            'detail_diri.date_of_birth'         => ['nullable', 'date'],
            'detail_diri.address'               => ['nullable', 'string'],
            'detail_diri.blood_type'            => ['nullable', 'string', 'in:a,b,ab,o,none'],
            'detail_diri.education_id'          => ['nullable', 'integer', 'exists:pendidikans,id'],
            'detail_diri.marriage_status_id'    => ['nullable', 'integer', 'exists:marriage_statuses,id'],
            'detail_diri.residential_area_id'   => ['nullable', 'integer', 'exists:cities,code'],
            'detail_diri.passport_photo'        => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'detail_diri.id_card_photo'         => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'detail_diri.drivers_license_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'detail_gaji'                       => ['sometimes', 'array'],
            'detail_gaji.salary_type'           => ['nullable', 'string', 'in:monthly,daily'],
            'detail_gaji.monthly_base_salary'   => ['nullable', 'numeric', 'min:0'],
            'detail_gaji.daily_base_salary'     => ['nullable', 'numeric', 'min:0'],
            'detail_gaji.meal_allowance'        => ['nullable', 'numeric', 'min:0'],
            'detail_gaji.bonus'                 => ['nullable', 'numeric', 'min:0'],
            'detail_gaji.allowance'             => ['nullable', 'numeric', 'min:0'],
            'detail_gaji.overtime'              => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422)
        );
    }
}
