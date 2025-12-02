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
            'description'           => ['sometimes', 'string', 'max:255'],
            'check_in_longitude'    => ['sometimes', 'decimal:1,10', 'min:-180', 'max:180'],
            'check_in_latitude'     => ['sometimes', 'decimal:1,10', 'min:-90', 'max:90'],
            'check_out_longitude'   => ['sometimes', 'decimal:1,10', 'min:-180', 'max:180'],
            'check_out_latitude'    => ['sometimes', 'decimal:1,10', 'min:-90', 'max:90'],
            'start_time'            => ['nullable', 'date_format:H:i:s'],
            'end_time'              => ['nullable', 'date_format:H:i:s', 'after:start_time'],
            'half_day'              => ['nullable', 'boolean'],
            'sick_note'             => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:5120'],
            'check_in_image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === null) return;

                    if (!preg_match('/^data:image\/(\w+);base64,/', $value, $m)) {
                        return $fail('Check-in image format invalid.');
                    }

                    $allowed = ['jpeg', 'jpg', 'png', 'webp'];
                    $ext = strtolower($m[1]);
                    if (!in_array($ext, $allowed)) {
                        return $fail('Check-in image type not allowed.');
                    }

                    $base64 = substr($value, strpos($value, ',') + 1);
                    $binary = base64_decode($base64, true);

                    if (!$binary) {
                        return $fail('Check-in image base64 decode error.');
                    }

                    if (strlen($binary) > 2 * 1024 * 1024) {
                        return $fail('Check-in image too large.');
                    }
                }
            ],
            'check_out_image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === null) return;

                    if (!preg_match('/^data:image\/(\w+);base64,/', $value, $m)) {
                        return $fail('Check-out image format invalid.');
                    }

                    $allowed = ['jpeg', 'jpg', 'png', 'webp'];
                    $ext = strtolower($m[1]);
                    if (!in_array($ext, $allowed)) {
                        return $fail('Check-out image type not allowed.');
                    }

                    $base64 = substr($value, strpos($value, ',') + 1);
                    $binary = base64_decode($base64, true);

                    if (!$binary) {
                        return $fail('Check-out image base64 decode error.');
                    }

                    if (strlen($binary) > 2 * 1024 * 1024) {
                        return $fail('Check-out image too large.');
                    }
                }
            ],
        ];
    }
}
