<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeTrainingIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'                 => ['nullable', 'string'],
            'status'            => ['nullable', 'integer'],
            'karyawan_id'       => [
                'nullable',
                Rule::when(
                    $this->input('karyawan_id') !== 'all',
                    ['integer', 'exists:karyawans,id']
                ),
            ],
            'training_type_id'  => [
                'nullable',
                Rule::when(
                    $this->input('training_type_id') !== 'all',
                    ['integer', 'exists:employee_training_type,id']
                ),
            ],
        ];
    }
}
