<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\PermissionIndexRequest;
use App\Http\Requests\PermissionStoreRequest;
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
            $permQ      = Permission::query()->filter($validated)->orderBy('name');
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

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
