<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class FaceRecognitionStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['required', 'integer', 'exists:karyawans,id'],
            'image_path'    => ['required', new Base64Image(['jpeg', 'jpg', 'png', 'webp'], 2 * 1024 * 1024)],
        ];
    }
}
