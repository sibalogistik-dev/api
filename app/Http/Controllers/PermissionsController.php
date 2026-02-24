<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\PermissionIndexRequest;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionsController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(PermissionIndexRequest $request)
    {
        try {
            $validated  = $request->validated();
            $permQ      = Permission::query()->filter($validated);
            $perm       = isset($validated['paginate']) && $validated['paginate'] ? $permQ->paginate($validated['perPage'] ?? 10) : $permQ->get();
            $transformedItems   = $perm instanceof LengthAwarePaginator ? $perm->getCollection() : $perm;
            $transformedperm  = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'description'   => $item->description,
                ];
            });
            if ($perm instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Permission data', $perm->setCollection($transformedperm));
            }
            return ApiResponseHelper::success('Permission data', $transformedperm);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get permission data', $e->getMessage());
        }
    }

    public function store(PermissionStoreRequest $request)
    {
        try {
            $permission = $this->permissionService->create($request->validated());
            return ApiResponseHelper::success('Permission data', $permission);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to add permission data', $e->getMessage());
        }
    }

    public function show($permission)
    {
        try {
            $perm = Permission::find($permission);
            if (!$perm) {
                return ApiResponseHelper::error('Permission not found', null, 404);
            }
            $data = [
                'id'            => $perm->id,
                'name'          => $perm->name,
                'description'   => $perm->description,
            ];
            return ApiResponseHelper::success('Permission data', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get permission data', $e->getMessage());
        }
    }

    public function update(PermissionUpdateRequest $request, $permission)
    {
        try {
            $perm = Permission::find($permission);
            if (!$perm) {
                throw new Exception('Permission data not found');
            }
            $updated = $this->permissionService->update($perm, $request->validated());
            return ApiResponseHelper::success('Permission successfully updated.', $updated);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to update permission data', $e->getMessage());
        }
    }

    public function destroy($permission)
    {
        try {
            $perm = Permission::find($permission);
            if (!$perm) {
                throw new Exception('Permission data not found');
            }
            $perm->delete();
            return ApiResponseHelper::success('Permission data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to delete permission data', $e->getMessage());
        }
    }
}
