<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Helpers\ApiResponseHelper;
use App\Http\Requests\CompanyIndexRequest;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
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
        } else {
            return ApiResponseHelper::success('Companies list', $transformedCompany);
        }
    }

    public function create()
    {
        //
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

    public function show($perusahaan)
    {
        $company = Perusahaan::withTrashed()->find($perusahaan);
        if ($company) {
            $data = [
                'id'                => $company->id,
                'name'              => $company->name,
                'codename'          => $company->codename
            ];
            $branches = $company->branches;
            if ($branches) {
                $data['branches'] = $branches->map(function ($branch) {
                    return [
                        'id'        => $branch->id,
                        'name'      => $branch->name,
                        'address'   => $branch->address,
                        'telephone' => $branch->telephone,
                        'province'  => $branch->village->district->city->province->name,
                        'city'      => $branch->village->district->city->name,
                        'district'  => $branch->village->district->name,
                        'village'   => $branch->village->name,
                    ];
                });
            }
            return ApiResponseHelper::success("Company's detail", $data);
        }
        return ApiResponseHelper::error('Company not found', [], 404);
    }

    public function update(CompanyUpdateRequest $request, Perusahaan $perusahaan)
    {
        try {
            $this->companyService->update($perusahaan, $request->validated());
            return ApiResponseHelper::success('Company data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating company data', $e->getMessage(), 500);
        }
    }

    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->branches()->delete();
        $delete = $perusahaan->delete();
        if ($delete) {
            return ApiResponseHelper::success('Company data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Company data failed to delete', null, 500);
        }
    }
}
