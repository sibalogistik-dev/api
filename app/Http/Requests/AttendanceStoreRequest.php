<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
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
            'description'           => ['sometimes', 'string', 'max:255'],
            'check_in_longitude'    => ['sometimes', 'decimal:1,10', 'min:-180', 'max:180'],
            'check_in_latitude'     => ['sometimes', 'decimal:1,10', 'min:-90', 'max:90'],
            'check_out_longitude'   => ['sometimes', 'decimal:1,10', 'min:-180', 'max:180'],
            'check_out_latitude'    => ['sometimes', 'decimal:1,10', 'min:-90', 'max:90'],
            'start_time'            => ['nullable', 'date_format:H:i:s'],
            'end_time'              => ['nullable', 'date_format:H:i:s', 'after:start_time'],
            'half_day'              => ['nullable', 'boolean'],
            'sick_note'             => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:5120'],
            'check_in_image'        => ['nullable', new Base64Image(['jpeg', 'jpg', 'png', 'webp'], 2 * 1024 * 1024)],
            'check_out_image'       => ['nullable', new Base64Image(['jpeg', 'jpg', 'png', 'webp'], 2 * 1024 * 1024)],
        ];
    }
}
