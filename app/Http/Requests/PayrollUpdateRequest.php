<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'       => ['nullable', 'integer', 'exists:karyawans,id'],
            'period_name'       => ['nullable', 'string'],
            'period_start'      => ['nullable', 'date'],
            'period_end'        => ['nullable', 'date'],
            'salary_type'       => ['nullable', 'string', 'in:monthly,daily'],
            'base_salary'       => ['nullable', 'integer'],
            'days'              => ['nullable', 'integer'],
            'present_days'      => ['nullable', 'integer'],
            'half_days'         => ['nullable', 'integer'],
            'absent_days'       => ['nullable', 'integer'],
            'sick_days'         => ['nullable', 'integer'],
            'leave_days'        => ['nullable', 'integer'],
            'permission_days'   => ['nullable', 'integer'],
            'off_days'          => ['nullable', 'integer'],
            'overtime_minutes'  => ['nullable', 'integer'],
            'late_minutes'      => ['nullable', 'integer'],
            'deductions'        => ['nullable', 'integer'],
            'allowances'        => ['nullable', 'integer'],
            'overtime'          => ['nullable', 'integer'],
            'compensation'      => ['nullable', 'integer'],
            'net_salary'        => ['nullable', 'integer'],
            'generated_at'      => ['nullable', 'date'],
        ];
    }
}
