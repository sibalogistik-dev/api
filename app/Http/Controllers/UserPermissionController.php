<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\UserPermissionIndexRequest;
use App\Http\Requests\UserPermissionStoreRequest;
use App\Http\Requests\UserPermissionUpdateRequest;
use App\Models\User;
use App\Services\UserPermissionService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

class UserPermissionController extends Controller
{
    protected $userPermissionService;

    public function __construct(UserPermissionService $userPermissionService)
    {
        $this->userPermissionService = $userPermissionService;
        $this->middleware('permission:hrd.user-permission|hrd.user-permission.index')->only('index');
        $this->middleware('permission:hrd.user-permission|hrd.user-permission.store')->only('store');
        $this->middleware('permission:hrd.user-permission|hrd.user-permission.show')->only('show');
        $this->middleware('permission:hrd.user-permission|hrd.user-permission.update')->only('update');
        $this->middleware('permission:hrd.user-permission|hrd.user-permission.destroy')->only('destroy');
    }

    public function index(UserPermissionIndexRequest $request)
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

    public function store(UserPermissionStoreRequest $request)
    {
        //
    }

    public function show($userPermission)
    {
        //
    }

    public function update(UserPermissionUpdateRequest $request, $userPermission)
    {
        //
    }

    public function destroy($userPermission)
    {
        //
    }
}
