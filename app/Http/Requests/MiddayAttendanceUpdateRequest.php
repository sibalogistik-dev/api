<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class MiddayAttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => ['sometimes', 'exists:karyawans,id'],
            'date_time'   => ['sometimes', 'date_format:Y-m-d H:i:s'],
            'image'       => ['sometimes', new Base64Image(['jpeg', 'jpg', 'png', 'webp'], 2 * 1024 * 1024)],
            'longitude'   => ['sometimes', 'numeric', 'min:-180', 'max:180'],
            'latitude'    => ['sometimes', 'numeric', 'min:-90', 'max:90'],
            'description' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
