<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MiddayAttendanceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
