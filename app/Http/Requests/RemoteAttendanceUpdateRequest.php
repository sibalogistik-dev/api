<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoteAttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['sometimes', 'integer', 'exists:karyawans,id'],
            'start_date'    => ['sometimes', 'date_format:Y-m-d'],
            'end_date'      => ['sometimes', 'date_format:Y-m-d'],
        ];
    }
}
