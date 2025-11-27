<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['sometimes', 'integer', 'exists:karyawans,id'],
            'start_time'    => ['sometimes', 'date'],
            'end_time'      => ['sometimes', 'date', 'after:start_time'],
            'approved'      => ['sometimes', 'boolean'],
            'description'   => ['sometimes', 'string'],
        ];
    }
}
