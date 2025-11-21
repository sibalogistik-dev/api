<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\CompanyIndexRequest;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Perusahaan;
use App\Services\CompanyService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class PerusahaanController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(CompanyIndexRequest $request)
    {
        $validated          = $request->validated();
        $companyQuery       = Perusahaan::query()->filter($validated)->orderBy('id', 'desc');
        $company            = isset($validated['paginate']) && $validated['paginate'] ? $companyQuery->paginate($validated['perPage'] ?? 10) : $companyQuery->get();
        $itemsToTransform   = $company instanceof LengthAwarePaginator ? $company->getCollection() : $company;
        $transformedCompany = $itemsToTransform->map(function ($item) {
            return [
                'id'                => $item->id,
                'name'              => $item->name,
                'codename'          => $item->codename,
                'branches_total'    => $item->branches->count(),
            ];
        });
        if ($company instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Companies list', $company->setCollection($transformedCompany));
        }
        return ApiResponseHelper::success('Companies list', $transformedCompany);
    }

    public function store(CompanyStoreRequest $request)
    {
        try {
            $company = $this->companyService->create($request->validated());
            return ApiResponseHelper::success('Company data has been added successfully', $company);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving company data', $e->getMessage(), 500);
        }
    }

    public function show($company)
    {
        $company = Perusahaan::find($company);
        if (!$company) {
            return ApiResponseHelper::error('Company not found', []);
        }
        $data = [
            'id'                => $company->id,
            'name'              => $company->name,
            'codename'          => $company->codename
        ];
        return ApiResponseHelper::success("Company's detail", $data);
    }

    public function update(CompanyUpdateRequest $request, Perusahaan $company)
    {
        try {
            $this->companyService->update($company, $request->validated());
            return ApiResponseHelper::success('Company data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating company data', $e->getMessage(), 500);
        }
    }

    public function destroy($company)
    {
        try {
            $company = Perusahaan::find($company);
            if (!$company) {
                throw new Exception('Company data not found');
            }
            $company->branches()->delete();
            $delete = $company->delete();
            if (!$delete) {
                throw new Exception('Company data failed to delete', 500);
            }
            return ApiResponseHelper::success('Company data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Company data failed to delete', $e->getMessage(), 500);
        }
    }

    public function companyBranches($company)
    {
        try {
            $company = Perusahaan::find($company);
            if (!$company) {
                throw new Exception('Company data not found');
            }
            $branches = $company->branches->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'address'           => $item->address,
                    'telephone'         => $item->telephone ?? null,
                    'province'          => $item->village->district->city->province->name,
                    'city'              => $item->village->district->city->name,
                    'district'          => $item->village->district->name,
                    'village'           => $item->village->name,
                ];
            });
            return ApiResponseHelper::success("Company's branches", $branches);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to fetch company branches", $e->getMessage(), $e->getCode());
        }
    }
}
