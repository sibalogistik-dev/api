<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaceRecognitionIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['sometimes', 'string'],
            'paginate'      => ['sometimes', 'boolean'],
            'perPage'       => ['sometimes', 'integer', 'min:1'],
            'employee_id'   => ['sometimes', 'integer', 'exists:employees,id'],
        ];
    }
}
