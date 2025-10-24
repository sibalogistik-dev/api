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
            'attendance_image'      => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            // optional
            'date'                  => ['nullable', 'date'],
            'start_time'            => ['nullable', 'date_format:H:i:s'],
            'end_time'              => ['nullable', 'date_format:H:i:s'],
        ];
    }
}
