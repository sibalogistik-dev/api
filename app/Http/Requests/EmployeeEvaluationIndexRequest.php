<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeEvaluationIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1'],
            'employee_id'   => [
                'nullable',
                Rule::when(
                    $this->input('employee_id') !== 'all',
                    ['integer', 'exists:karyawans,id'],
                    ['string']
                ),
            ],
            'evaluator_id'  => [
                'nullable',
                Rule::when(
                    $this->input('evaluator_id') !== 'all',
                    ['integer', 'exists:karyawans,id'],
                    ['string']
                ),
            ],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date'],
        ];
    }
}
