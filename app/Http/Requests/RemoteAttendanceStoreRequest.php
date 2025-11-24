<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoteAttendanceStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['required', 'integer', 'exists:karyawans,id'],
            'start_date'    => ['required', 'date', 'date_format:Y-m-d'],
            'end_date'      => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }
}
