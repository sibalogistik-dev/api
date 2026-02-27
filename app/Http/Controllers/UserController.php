<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('permission:hrd.users|hrd.users.index')->only('index');
        $this->middleware('permission:hrd.users|hrd.users.store')->only('store');
        $this->middleware('permission:hrd.users|hrd.users.show')->only('show');
        $this->middleware('permission:hrd.users|hrd.users.update')->only('update');
        $this->middleware('permission:hrd.users|hrd.users.destroy')->only('destroy');
    }

    public function index(UserIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $userQ              = User::query()->filter($validated);
            $user               = isset($validated['paginate']) && $validated['paginate'] ? $userQ->paginate($validated['perPage'] ?? 10) : $userQ->get();
            $transformedItems   = $user instanceof LengthAwarePaginator ? $user->getCollection() : $user;
            $transformedUser    = $transformedItems->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'email'             => $item->email,
                    'username'          => $item->username,
                    'user_type'         => $item->user_type,
                    'total_roles'       => $item->roles->count(),
                    'total_permission'  => $item->getAllPermissions()->count(),
                ];
            });

            if ($user instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('User list', $user->setCollection($transformedUser));
            }
            return ApiResponseHelper::success('User list', $transformedUser);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to retrieve user list', $e->getMessage());
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = $this->userService->create($request->validated());
            return ApiResponseHelper::success('User created successfully', $user);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving user data', $e->getMessage());
        }
    }

    public function show($users)
    {
        try {
            $user = User::find($users);
            if (!$user) {
                return ApiResponseHelper::error('User not found');
            }
            $data = [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'username'      => $user->username,
                'user_type'     => $user->user_type,
            ];
            return ApiResponseHelper::success('User retrieved successfully', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when retrieving user data', $e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, $users)
    {
        try {
            $user = User::find($users);
            if (!$user) {
                return ApiResponseHelper::error('User not found');
            }
            $this->userService->update($user, $request->validated());
            return ApiResponseHelper::success('User updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating user data', $e->getMessage());
        }
    }

    public function destroy($users)
    {
        try {
            $user = User::find($users);
            if (!$user) {
                throw new Exception('User not found');
            }
            $delete = $user->delete();
            if (!$delete) {
                throw new Exception('Failed to delete user');
            }
            return ApiResponseHelper::success('User deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting user data', $e->getMessage());
        }
    }
}
