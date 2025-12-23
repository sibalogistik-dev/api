<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeTrainingIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'                 => ['nullable', 'string'],
            'paginate'          => ['nullable', 'boolean'],
            'perPage'           => ['nullable', 'integer', 'min:1'],
            'status'            => [
                'nullable',
                Rule::when(
                    $this->input('status') !== 'all',
                    ['integer']
                ),
            ],
            'employee_id'       => [
                'nullable',
                Rule::when(
                    $this->input('employee_id') !== 'all',
                    ['integer', 'exists:karyawans,id']
                ),
            ],
            'training_type_id'  => [
                'nullable',
                Rule::when(
                    $this->input('training_type_id') !== 'all',
                    ['integer', 'exists:employee_training_types,id']
                ),
            ],
        ];
    }
}
