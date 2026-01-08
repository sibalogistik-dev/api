<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTrainingScheduleStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_training_id'  => ['required', 'integer', 'exists:employee_trainings,id'],
            'mentor_id'             => ['sometimes', 'integer', 'exists:karyawans,id'],
            'schedule_time'         => ['required', 'date'],
            'title'                 => ['required', 'string', 'max:255'],
            'activity_description'  => ['required', 'string'],
            'activity_result'       => ['nullable', 'string'],
            'mentor_notes'          => ['nullable', 'string'],
            'mentor_assessment'     => ['nullable', 'string'],
        ];
    }
}
