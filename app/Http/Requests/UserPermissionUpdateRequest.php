<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPermissionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'permission_id' => ['required', 'integer', 'exists:permissions,id']
        ];
    }
}
