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
            'username'  => ['required', 'string'],
            'password'  => ['required', 'string'],
            'app_access_key'  => ['required', 'string', Rule::in($this->allowedDevices)],
        ]);

        if ($validator->fails()) {
            return ApiResponseHelper::error('Validasi data gagal!', $validator->errors(), 422);
        }

        $login_type = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$login_type => $request->username, 'password' => $request->password])) {
            $user = Auth::user()->load(['roles.permissions', 'permissions']);
            if (!$user->hasPermissionTo($request->app_access_key)) {
                Auth::logout();
                return ApiResponseHelper::error('Anda tidak memiliki izin untuk mengakses aplikasi ini.', null, 403);
            }
            $token = $user->createToken($request->app_access_key)->plainTextToken;
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
        $user = Auth::user()->load([
            'roles:id,name',
            'roles.permissions:id,name,description',
            'permissions',
            'employee' => fn($query) => $query->with([
                'jobTitle:id,name',
                'manager:id,name',
                'branch:id,name',
                'employeeDetails:id,employee_id,gender,religion_id,phone_number,place_of_birth_id,date_of_birth,address,blood_type,education_id,marriage_status_id,residential_area_id,passport_photo,id_card_photo',
                'employeeDetails.religion:id,name',
                'employeeDetails.birthPlace:code,name',
                'employeeDetails.education:id,name',
                'employeeDetails.residentialArea:code,name',
                'employeeDetails.marriageStatus:id,name',
                'salaryDetails:id,monthly_base_salary,daily_base_salary,meal_allowance,bonus,allowance',
                'salaryHistory',
            ]),
        ]);
        return ApiResponseHelper::success('Data profil pengguna berhasil diambil', $user);
    }
}
