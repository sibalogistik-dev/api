<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\CityIndexRequest;
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
                return ApiResponseHelper::success('Provinces data', $province->setCollection($transformedProvince));
            } else {
                return ApiResponseHelper::success('Provinces data', $transformedProvince);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get all province data', $e->getMessage());
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
        try {
            $province = Province::where('code', $code)->first();
            if (!$province) {
                throw new Exception('Province not found');
            }
            $cities = $province->cities;
            return ApiResponseHelper::success('Cities data of ' . $province->name . ' province', $cities);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get this province\'s city data', $e->getMessage());
        }
    }

    #endregion

    #region City

    public function getAllCity(CityIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $cityQuery          = City::query()->filter($validated);
            $city               = isset($validated['paginate']) && $validated['paginate'] ? $cityQuery->paginate($validated['paginate'] ?? 10) : $cityQuery->get();
            $itemToTransform    = $city instanceof LengthAwarePaginator ? $city->getCollection() : $city;
            $tranformedCity     = $itemToTransform->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'code'          => $item->code,
                    'province_code' => $item->province_code,
                    'name'          => $item->name,
                    'meta'          => $item->meta,
                ];
            });
            if ($city instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Cities data', $city->setCollection($tranformedCity));
            } else {
                return ApiResponseHelper::success('Cities data', $tranformedCity);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get city data', $e->getMessage());
        }
    }

    public function getCity($code)
    {
        try {
            $city = City::where('code', $code)->first();
            if (!$city) {
                throw new Exception('City not found');
            }
            return ApiResponseHelper::success('City data', $city);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get city data', $e->getMessage());
        }
    }

    public function getCityDistrict($code)
    {
        $city = City::where('code', $code)->first();
        if (!$city) {
            return ApiResponseHelper::error('Data Kota/Kabupaten tidak ditemukan.', []);
        }
        $districts = $city->districts;
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
