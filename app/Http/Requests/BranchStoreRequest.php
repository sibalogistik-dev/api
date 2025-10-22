<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:255'],
            'telephone'     => ['nullable', 'string', 'max:255'],
            'city_id'       => ['required', 'integer', 'exists:indonesia_cities,code'],
            'company_id'    => ['required', 'integer', 'exists:perusahaans,id'], 
            'start_time'    => ['required', 'date_format:H:i:s'],
            'end_time'      => ['required', 'date_format:H:i:s', 'after:start_time'],
            'latitude'      => ['required', 'numeric', 'min:-90', 'max:90'],
            'longitude'     => ['required', 'numeric', 'min:-180', 'max:180'],
        ];
    }
}
