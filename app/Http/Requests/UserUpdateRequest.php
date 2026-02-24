<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['sometimes', 'string', 'max:255'],
            'email'     => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['sometimes', 'string', 'min:8', 'confirmed'],
            'username'  => ['sometimes', 'string', 'max:255', 'unique:users'],
            'user_type' => ['sometimes', 'string', 'in:employee,director,customer'],
        ];
    }
}
