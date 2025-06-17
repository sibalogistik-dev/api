<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'device_name' => ['required', 'string', Rule::in($this->allowedDevices)],
        ]);

        if (! in_array($request->device_name, $this->allowedDevices)) {
            return ApiResponseHelper::error(
                'Nama perangkat tidak valid.',
                null,
                403,
            );
        }

        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt([$login_type => $request->login, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken($request->device_name)->plainTextToken;
            return ApiResponseHelper::success('Login Berhasil!', [
                'token' => $token,
                'user' => $user,
            ]);
            // return response()->json([
            //     'message' => 'Login berhasil',
            //     'token' => $token,
            //     'user' => $user
            // ], 200);
        }

        return ApiResponseHelper::error('Username atau password salah', null, 401);
        // return response()->json([
        //     'message' => 'Username atau password salah'
        // ], 401);
    }

    public function loginError()
    {
        return ApiResponseHelper::error(
            'Otentikasi diperlukan untuk mengakses sumber daya ini',
            null,
            401
        );
    }

    public function user(Request $request)
    {
        $user = $request->user()->load(['roles.permissions', 'permissions']);
        return ApiResponseHelper::success('Data profil pengguna berhasil diambil', $user);
    }
}
