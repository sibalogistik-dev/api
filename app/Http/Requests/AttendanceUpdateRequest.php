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
            'attendance_status_id'  => ['nullable', 'integer', 'exists:status_absensis,id'],
            'description'           => ['nullable', 'string', 'max:255'],
            'start_time'            => ['nullable', 'date_format:H:i:s'],
            'end_time'              => ['nullable', 'date_format:H:i:s', 'after:start_time'],
            'half_day'              => ['nullable', 'boolean'],
            'sick_note'             => ['nullable', 'file', 'mimetypes:pdf,jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
