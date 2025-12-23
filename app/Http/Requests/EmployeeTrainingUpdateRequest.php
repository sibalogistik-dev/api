<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTrainingUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'employee_id'       => ['sometimes', 'integer', 'exists:karyawans,id'],
            'training_type_id'  => ['sometimes', 'integer', 'exists:employee_training_types,id'],
            'start_date'        => ['sometimes', 'date'],
            'notes'             => ['sometimes', 'string', 'max:255'],
            'status'            => ['sometimes', 'boolean']
        ];
    }
}
