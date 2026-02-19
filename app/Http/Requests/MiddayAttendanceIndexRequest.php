<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MiddayAttendanceIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'             => ['nullable', 'string'],
            'employee_id'   => ['nullable', 'integer'],
            'date'          => ['nullable', 'date'],
            'branch'        => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
