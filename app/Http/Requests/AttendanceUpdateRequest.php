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
            'attendance_status_id'  => ['sometimes', 'required', 'integer', 'exists:status_absensis,id'],
            'description'           => ['sometimes', 'required', 'string', 'max:255'],
            'start_time'            => ['sometimes', 'required', 'date_format:H:i:s'],
            'end_time'              => ['sometimes', 'required', 'date_format:H:i:s', 'after:start_time'],
            'half_day'              => ['sometimes', 'required', 'boolean'],
            'sick_note'             => ['sometimes', 'required', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
