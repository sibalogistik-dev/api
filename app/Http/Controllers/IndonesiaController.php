<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ProvinceIndexRequest;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndonesiaController extends Controller
{
    #region Province

    public function getAllProvince(ProvinceIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $provinceQuery          = Province::query()->filter($validated);
            $province               = isset($validated['paginate']) && $validated['paginate'] ? $provinceQuery->paginate($validated['perPage'] ?? 10) : $provinceQuery->get();
            $itemsToTransform       = $province instanceof LengthAwarePaginator ? $province->getCollection() : $province;
            $transformedProvince    = $itemsToTransform->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'code'  => $item->code,
                    'name'  => $item->name,
                    'meta'  => $item->meta,
                ];
            });
            if ($province instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Province list', $province->setCollection($transformedProvince));
            } else {
                return ApiResponseHelper::success('Province list', $transformedProvince);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get province data', $e->getMessage());
        }
    }

    public function getProvince($code)
    {
        try {
            $province = Province::where('code', $code)->first();
            if (!$province) {
                throw new Exception('Province not found');
            }
            return ApiResponseHelper::success('Province data', $province);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get province data', $e->getMessage());
        }
    }

    public function getProvinceCity($code)
    {
        $cities = Province::with('cities')
            ->where('code', $code)
            ->first()->cities;
        if (!$cities) {
            return ApiResponseHelper::error('Data Kota tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kota berhasil diambil.', $cities);
    }

    #endregion

    #region City

    public function getAllCity(Request $request)
    {
        $search     = $request->q;
        $paginate   = $request->paginate ?? false;
        $perPage    = $request->perPage ?? 10;
        $cities     = City::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(
                $paginate,
                fn($query) => $query->paginate($perPage),
                fn($query) => $query->get()
            );
        if ($cities->isEmpty()) {
            return ApiResponseHelper::error('Data Kota tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kota berhasil diambil.', $cities);
    }

    public function getCity($code)
    {
        $city = City::where('code', $code)->first();
        if (!$city) {
            return ApiResponseHelper::error('Data Kota tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kota berhasil diambil.', $city);
    }

    public function getCityDistrict($code)
    {
        $districts = City::with('districts')
            ->where('code', $code)
            ->first()
            ->districts;
        if (!$districts) {
            return ApiResponseHelper::error('Data Kecamatan tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kecamatan berhasil diambil.', $districts);
    }

    #endregion

    #region District

    public function getAllDistrict(Request $request)
    {
        $search     = $request->q;
        $paginate   = $request->paginate ?? false;
        $perPage    = $request->perPage ?? 10;
        $districts  = District::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(
                $paginate,
                fn($query) => $query->paginate($perPage),
                fn($query) => $query->get()
            );
        if ($districts->isEmpty()) {
            return ApiResponseHelper::error('Data Kecamatan tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kecamatan berhasil diambil.', $districts);
    }

    public function getDistrict($code)
    {
        $district = District::with('villages')
            ->where('code', $code)
            ->first();
        if (!$district) {
            return ApiResponseHelper::error('Data Kecamatan tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kecamatan berhasil diambil.', $district);
    }

    public function getDistrictVillage($code)
    {
        $villages = District::with('villages')
            ->where('code', $code)
            ->first()->villages;
        if (!$villages) {
            return ApiResponseHelper::error('Data Kelurahan tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kelurahan berhasil diambil.', $villages);
    }

    #endregion

    #region Village

    public function getAllVillage(Request $request)
    {
        $search     = $request->q;
        $paginate   = $request->paginate ?? false;
        $perPage    = $request->perPage ?? 10;
        $villages   = Village::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(
                $paginate,
                fn($query) => $query->paginate($perPage),
                fn($query) => $query->get()
            );
        if ($villages->isEmpty()) {
            return ApiResponseHelper::error('Data Kelurahan tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kelurahan berhasil diambil.', $villages);
    }

    public function getVillage($code)
    {
        $village = Village::where('code', $code)
            ->first();
        if (!$village) {
            return ApiResponseHelper::error('Data Kelurahan tidak ditemukan.', []);
        }
        return ApiResponseHelper::success('Data Kelurahan berhasil diambil.', $village);
    }

    #endregion
}
