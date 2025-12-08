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
            'attendance_status_id'  => ['sometimes', 'integer', 'exists:status_absensis,id'],
            'description'           => ['sometimes', 'string', 'max:255'],
            'start_time'            => ['sometimes', 'date_format:H:i:s'],
            'end_time'              => ['sometimes', 'date_format:H:i:s', 'after:start_time'],
            'half_day'              => ['sometimes', 'boolean'],
            'sick_note'             => ['sometimes', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
