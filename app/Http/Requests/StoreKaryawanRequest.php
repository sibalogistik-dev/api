<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'              => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8'],

            'name'                  => ['required', 'string', 'max:255'],
            'npk'                   => ['required', 'string', 'max:50', 'unique:karyawans,npk'],

            'job_title_id'          => ['required', 'integer', 'exists:jabatans,id'],
            'branch_id'             => ['required', 'integer', 'exists:cabangs,id'],
            'start_date'            => ['required', 'date'],
            'end_date'              => ['nullable', 'date'],
            'contract'              => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'bank_account_number'   => ['required', 'string', 'max:50', 'unique:karyawans,bank_account_number'],
            'gender'                => ['required', 'in:laki-laki,perempuan'],
            'religion_id'           => ['required', 'integer', 'exists:agamas,id'],

            'phone_number'          => ['required', 'string', 'max:20'],

            'place_of_birth_id'     => ['required', 'integer', 'exists:indonesia_cities,code'],
            'date_of_birth'         => ['required', 'date'],
            'address'               => ['required', 'string'],
            'blood_type'            => ['nullable', 'string', 'in:a,b,ab,o,none'],
            'education_id'          => ['required', 'integer', 'exists:pendidikans,id'],

            'marriage_status_id'    => ['required', 'integer', 'exists:marriage_statuses,id'],

            'residential_area_id'   => ['required', 'integer', 'exists:indonesia_cities,code'],

            'passport_photo'        => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'id_card_photo'         => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'drivers_license_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'monthly_base_salary'   => ['required', 'numeric', 'min:0'],
            'daily_base_salary'     => ['required', 'numeric', 'min:0'],
            'meal_allowance'        => ['required', 'numeric', 'min:0'],
            'bonus'                 => ['required', 'numeric', 'min:0'],
            'allowance'             => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422)
        );
    }
}
