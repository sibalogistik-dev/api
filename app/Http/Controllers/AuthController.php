<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(Request $request) {
        $input = $request->only(['login', 'password']);
        $fieldType = filter_var($input['login'], FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($input['login']) ? 'phone' : 'username');

        $credentials = [
            $fieldType => $input['login'],
            'password' => $input['password']
        ];

        if (Auth::attempt($credentials)) {
            return response()
                ->json([
                    'message' => 'Login successful'
                ], 200);
        }

        return response()
            ->json([
                'message' => 'Invalid credentials'
            ], 401);
    }
}
