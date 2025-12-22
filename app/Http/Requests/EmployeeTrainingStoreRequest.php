<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTrainingStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'       => ['required', 'integer', 'exists:karyawans,id'],
            'training_type_id'  => ['required', 'integer', 'exists:employee_training_types,id'],
            'start_date'        => ['required', 'date'],
            'notes'             => ['required', 'string', 'max:255'],
            'status'            => ['required', 'boolean']
        ];
    }
}
