<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRollingScheduleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'       => ['required', 'integer', 'exists:karyawans,id'],
            'from_branch_id'    => ['required', 'integer', 'exists:cabangs,id'],
            'to_branch_id'      => ['required', 'integer', 'exists:cabangs,id'],
            'start_date'        => ['required', 'date', 'required_with:end_date'],
            'end_date'          => ['required', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
        ];
    }
}
