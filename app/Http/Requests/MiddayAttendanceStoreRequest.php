<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MiddayAttendanceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:karyawans,id'],
            'date_time'   => ['required', 'date_format:Y-m-d H:i:s'],
            'image'       => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'longitude'   => ['required', 'numeric', 'min:-180', 'max:180'],
            'latitude'    => ['required', 'numeric', 'min:-90', 'max:90'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
