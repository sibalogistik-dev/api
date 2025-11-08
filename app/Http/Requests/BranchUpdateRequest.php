<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'              => ['nullable', 'string', 'max:255'],
            'address'           => ['nullable', 'string', 'max:255'],
            'telephone'         => ['nullable', 'string', 'max:255'],
            'village_id'        => ['nullable', 'integer', 'exists:indonesia_villages,code'],
            'company_id'        => ['nullable', 'integer', 'exists:perusahaans,id'],
            'start_time'        => ['nullable', 'date_format:H:i:s'],
            'end_time'          => ['nullable', 'date_format:H:i:s', 'after:start_time'],
            'latitude'          => ['nullable', 'numeric', 'min:-90', 'max:90'],
            'longitude'         => ['nullable', 'numeric', 'min:-180', 'max:180'],
            'attendance_radius' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
