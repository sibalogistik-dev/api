<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEvaluationStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'       => ['required', 'integer', 'exists:karyawans,id'],
            'evaluator_id'      => ['required', 'integer', 'exists:karyawans,id'],
            'evaluation_date'   => ['required', 'date'],
            'description'       => ['required', 'string'],
        ];
    }
}
