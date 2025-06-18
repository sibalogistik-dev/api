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

        if (!in_array($request->device_name, $this->allowedDevices)) {
            return ApiResponseHelper::error(
                'Nama perangkat tidak valid.',
                null,
                403,
            );
        }

        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt([$login_type => $request->login, 'password' => $request->password])) {
            $user = Auth::user()->load([
                'roles.permissions',
                'permissions',
            ]);
            $token = $user->createToken($request->device_name)->plainTextToken;
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
            $role = $user->roles->first()?->name ?? 'unknown';
            $permissions = $user->getAllPermissions()->pluck('name');
            return ApiResponseHelper::success('Login Berhasil!', [
                'token' => $token,
                'user' => $data,
                'role' => $role,
                'permissions' => $permissions,
            ]);
        }

        return ApiResponseHelper::error('Username atau password salah', null, 401);
    }

    public function loginHRDApp(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'required|string|in:hrd app',
        ]);
        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt([$login_type => $request->login, 'password' => $request->password])) {
            $user = Auth::user()->load(['roles.permissions', 'permissions']);
            if (!$user->hasPermissionTo('hrd app')) {
                Auth::logout();
                return ApiResponseHelper::error('Anda tidak memiliki akses ke aplikasi HRD.', null, 403);
            }
            $token = $user->createToken($request->device_name)->plainTextToken;
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
            $role = $user->roles->first()?->name ?? 'unknown';
            $permissions = $user->getAllPermissions()->pluck('name');
            return ApiResponseHelper::success('Login Berhasil!', [
                'token' => $token,
                'user' => $data,
                'role' => $role,
                'permissions' => $permissions,
            ]);
        }

        return ApiResponseHelper::error('Username atau password salah', null, 401);
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
        $user = $request->user()->load([
            'roles.permissions',
            'permissions',
        ]);
        return ApiResponseHelper::success('Data profil pengguna berhasil diambil', $user);
    }
}
