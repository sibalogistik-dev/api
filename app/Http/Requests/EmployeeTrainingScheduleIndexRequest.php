<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeTrainingScheduleIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'                     => ['nullable', 'string'],
            'paginate'              => ['nullable', 'boolean'],
            'perPage'               => ['nullable', 'integer', 'min:1'],
            'schedule_time'         => ['nullable', 'date'],
            'mentor_id'             => [
                'nullable',
                Rule::when(
                    $this->input('mentor_id') !== 'all',
                    ['integer', 'exists:karyawans,id']
                ),
            ],
            'employee_id'           => [
                'nullable',
                Rule::when(
                    $this->input('employee_id') !== 'all',
                    ['integer', 'exists:karyawans,id']
                ),
            ],
            'employee_training_id'  => [
                'nullable',
                Rule::when(
                    $this->input('employee_training_id') !== 'all',
                    ['integer', 'exists:employee_trainings,id']
                ),
            ],
        ];
    }
}
