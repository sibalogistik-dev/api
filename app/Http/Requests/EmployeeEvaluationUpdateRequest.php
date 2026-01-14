<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEvaluationUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'       => ['sometimes', 'integer', 'exists:karyawans,id'],
            'evaluator_id'      => ['sometimes', 'integer', 'exists:karyawans,id'],
            'evaluation_date'   => ['sometimes', 'date'],
            'description'       => ['sometimes', 'string'],
        ];
    }
}
