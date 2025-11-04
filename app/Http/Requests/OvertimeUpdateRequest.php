<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'   => ['nullable', 'integer', 'exists:karyawans,id'],
            'start_time'    => ['nullable', 'date'],
            'end_time'      => ['nullable', 'date', 'after:start_time'],
            'approved'      => ['nullable', 'boolean'],
        ];
    }
}
