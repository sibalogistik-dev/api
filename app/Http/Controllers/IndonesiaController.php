<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\CityIndexRequest;
use App\Http\Requests\DistrictIndexRequest;
use App\Http\Requests\ProvinceIndexRequest;
use App\Http\Requests\VillageIndexRequest;
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
            $provinceQ          = Province::query()->filter($validated);
            $province               = isset($validated['paginate']) && $validated['paginate'] ? $provinceQ->paginate($validated['perPage'] ?? 10) : $provinceQ->get();
            $transformedItems       = $province instanceof LengthAwarePaginator ? $province->getCollection() : $province;
            $transformedProvince    = $transformedItems->map(function ($item) {
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
                throw new Exception('Province data not found');
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
                throw new Exception('Province data not found');
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
            $cityQ          = City::query()->filter($validated);
            $city               = isset($validated['paginate']) && $validated['paginate'] ? $cityQ->paginate($validated['paginate'] ?? 10) : $cityQ->get();
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
                throw new Exception('City data not found');
            }
            return ApiResponseHelper::success('City data', $city);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get city data', $e->getMessage());
        }
    }

    public function getCityDistrict($code)
    {
        try {
            $city = City::where('code', $code)->first();
            if (!$city) {
                throw new Exception('City data not found');
            }
            $districts = $city->districts;
            return ApiResponseHelper::success('Districts data of ' . $city->name, $districts);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get this city\'s districts data', $e->getMessage());
        }
    }

    #endregion

    #region District

    public function getAllDistrict(DistrictIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $districtQ          = District::query()->filter($validated);
            $district               = isset($validated['paginate']) && $validated['paginate'] ? $districtQ->paginate($validated['perPage'] ?? 10) : $districtQ->get();
            $transformedItems       = $district instanceof LengthAwarePaginator ? $district->getCollection() : $district;
            $transformedDistrict    = $transformedItems->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'code'      => $item->code,
                    'city_code' => $item->city_code,
                    'name'      => $item->name,
                    'meta'      => $item->meta,
                ];
            });
            if ($district instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Districts data', $district->setCollection($transformedDistrict));
            } else {
                return ApiResponseHelper::success('Districts data', $transformedDistrict);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get district data');
        }
    }

    public function getDistrict($code)
    {
        try {
            $district = District::with('villages')
                ->where('code', $code)
                ->first();
            if (!$district) {
                throw new Exception('District data not found');
            }
            return ApiResponseHelper::success('District data', $district);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get district data', $e->getMessage());
        }
    }

    public function getDistrictVillage($code)
    {
        try {
            $district = District::with('villages')
                ->where('code', $code)
                ->first();
            if (!$district) {
                throw new Exception('District data not found');
            }
            $villages = $district->villages;
            return ApiResponseHelper::success('Villages data of ' . $district->name, $villages);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get this district\'s village data', $e->getMessage());
        }
    }

    #endregion

    #region Village

    public function getAllVillage(VillageIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $villageQ       = Village::query()->filter($validated);
            $village            = isset($validated['paginate']) && $validated['paginate'] ? $villageQ->paginate($validated['perPage'] ?? 10) : $villageQ->get();
            $transformedItems   = $village instanceof LengthAwarePaginator ? $village->getCollection() : $village;
            $transformedVillage = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'code'          => $item->code,
                    'district_code' => $item->district_code,
                    'name'          => $item->name,
                    'meta'          => $item->meta,
                ];
            });
            if ($village instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Villages data', $village->setCollection($transformedVillage));
            } else {
                return ApiResponseHelper::success('Villages data', $transformedVillage);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get village data', $e->getMessage());
        }
    }

    public function getVillage($code)
    {
        try {
            $village = Village::where('code', $code)
                ->first();
            if (!$village) {
                throw new Exception('Village data not found');
            }
            return ApiResponseHelper::success('Village data', $village);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get village data.', []);
        }
    }

    #endregion
}
