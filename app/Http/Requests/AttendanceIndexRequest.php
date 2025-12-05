<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string'],
            'employee_id'   => ['nullable', 'integer'],
            'date'          => ['nullable', 'date'],
            'branch'        => ['nullable', 'string'],
            'status'        => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
