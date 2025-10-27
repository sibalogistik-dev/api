<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'attendance_status_id'  => ['required', 'integer', 'exists:status_absensis,id'],
            'description'           => ['required', 'string', 'max:255'],
            'start_time'            => ['required', 'date_format:H:i:s'],
            'end_time'              => ['nullable', 'date_format:H:i:s', 'after:start_time'],
        ];
    }
}
