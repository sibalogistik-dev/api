<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRollingScheduleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'       => ['sometimes', 'integer', 'exists:karyawans,id'],
            'from_branch_id'    => ['sometimes', 'integer', 'exists:cabangs,id'],
            'to_branch_id'      => ['sometimes', 'integer', 'exists:cabangs,id'],
            'start_date'        => ['sometimes', 'date', 'required_with:end_date'],
            'end_date'          => ['sometimes', 'date', 'required_with:start_date', 'after_or_equal:start_date']
        ];
    }
}
