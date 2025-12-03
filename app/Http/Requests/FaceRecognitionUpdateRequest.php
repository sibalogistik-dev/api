<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaceRecognitionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['sometimes', 'integer', 'exists:employees,id'],
            'image_path'    => ['sometimes', 'string'],
        ];
    }
}
