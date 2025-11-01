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
        $karyawanId = $this->route('employee')->id;

        return [
            'name'                  => ['nullable', 'string', 'max:255'],
            'npk'                   => ['nullable', 'string', 'max:50', Rule::unique('karyawans', 'npk')->ignore($karyawanId)],

            'job_title_id'          => ['nullable', 'integer', 'exists:jabatans,id'],
            'branch_id'             => ['nullable', 'integer', 'exists:cabangs,id'],
            'start_date'            => ['nullable', 'date'],
            'end_date'              => ['nullable', 'date'],
            'contract'              => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'bank_account_number'   => ['nullable', 'string', 'max:50', Rule::unique('karyawans', 'bank_account_number')->ignore($karyawanId)],
            'gender'                => ['nullable', 'in:laki-laki,perempuan'],
            'religion_id'           => ['nullable', 'integer', 'exists:agamas,id'],

            'phone_number'          => ['nullable', 'string', 'max:20'],

            'place_of_birth_id'     => ['nullable', 'integer', 'exists:indonesia_cities,code'],
            'date_of_birth'         => ['nullable', 'date'],
            'address'               => ['nullable', 'string'],
            'blood_type'            => ['nullable', 'string', 'in:a,b,ab,o,none'],
            'education_id'          => ['nullable', 'integer', 'exists:pendidikans,id'],

            'marriage_status_id'    => ['nullable', 'integer', 'exists:marriage_statuses,id'],

            'residential_area_id'   => ['nullable', 'integer', 'exists:indonesia_cities,code'],

            'passport_photo'        => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'id_card_photo'         => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'drivers_license_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'monthly_base_salary'   => ['nullable', 'numeric', 'min:0'],
            'daily_base_salary'     => ['nullable', 'numeric', 'min:0'],
            'meal_allowance'        => ['nullable', 'numeric', 'min:0'],
            'bonus'                 => ['nullable', 'numeric', 'min:0'],
            'allowance'             => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422)
        );
    }
}
