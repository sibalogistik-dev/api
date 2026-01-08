<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTrainingScheduleUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_training_id'  => ['sometimes', 'integer', 'exists:employee_trainings,id'],
            'mentor_id'             => ['sometimes', 'integer', 'exists:karyawans,id'],
            'schedule_time'         => ['sometimes', 'date'],
            'title'                 => ['sometimes', 'string', 'max:255'],
            'activity_description'  => ['sometimes', 'string'],
            'activity_result'       => ['sometimes', 'string'],
            'mentor_notes'          => ['sometimes', 'string'],
            'mentor_assessment'     => ['sometimes', 'string'],
        ];
    }
}
