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
            'start_time'    => ['required', 'date'],
            'end_time'      => ['required', 'date'],
        ];
    }
}
