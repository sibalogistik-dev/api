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
            'date_time'   => ['sometimes', 'date_format:Y-m-d H:i:s'],
            'description' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
