<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string',
            'password'  => 'required|string',
            'app_name'  => ['required', 'string', Rule::in($this->allowedDevices)],
        ]);

        if ($validator->fails()) {
            return ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422);
        }

        $login_type = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$login_type => $request->username, 'password' => $request->password])) {
            $user = Auth::user()->load(['roles.permissions', 'permissions']);
            if (!$user->hasPermissionTo($request->app_name)) {
                Auth::logout();
                return ApiResponseHelper::error('Anda tidak memiliki izin untuk mengakses aplikasi ini.', null, 403);
            }
            $token = $user->createToken($request->app_name)->plainTextToken;
            $data = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email
            ];
            $roles = $user->getRoleNames();
            $permissions = $user->getAllPermissions()->pluck('name');
            return ApiResponseHelper::success('Login Berhasil!', [
                'token'         => $token,
                'user'          => $data,
                'roles'         => $roles,
                'permissions'   => $permissions,
            ]);
        }

        return ApiResponseHelper::error('Username atau password salah!', null, 401);
    }

    public function loginError()
    {
        return ApiResponseHelper::error('Otentikasi diperlukan untuk mengakses sumber daya ini', null, 401);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load([
            'roles.permissions',
            'permissions',
            'karyawan' => fn($query) => $query->with(['jabatan', 'cabang', 'detail_diri', 'detail_gaji', 'histori_gaji']),
        ]);
        return ApiResponseHelper::success('Data profil pengguna berhasil diambil', $user);
    }
}
