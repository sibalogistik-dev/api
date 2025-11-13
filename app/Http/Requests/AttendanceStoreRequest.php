<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'           => ['required', 'integer', 'exists:karyawans,id'],
            'attendance_status_id'  => ['required', 'integer', 'exists:status_absensis,id'],
            'description'           => ['required', 'string', 'max:255'],
            'longitude'             => ['required', 'decimal:1,10', 'min:-180', 'max:180'],
            'latitude'              => ['required', 'decimal:1,10', 'min:-90', 'max:90'],
            'check_in_image'        => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'check_out_image'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            // 'attendance_image'      => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'half_day'              => ['nullable', 'boolean'],
        ];
    }
}
