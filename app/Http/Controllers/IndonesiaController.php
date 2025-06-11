<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class IndonesiaController extends Controller {
    #region Province
    /**
     * Get all provinces or a specific province by its code.
     *
     * @param string|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvince($code = null) {
        if (is_null($code)) {
            $province = Province::all();
        } else {
            $province = Province::with('cities')
                ->where('code', $code)
                ->first();
            if (!$province) {
                return ApiResponseHelper::error('Province not found.', 404);
            }
        }
        return ApiResponseHelper::success($province, 'Province retrieved successfully.');
    }

    /**
     * Get cities in a specific province.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinceCity($code) {
        $cities = Province::with('cities')
            ->where('code', $code)
            ->first()->cities;
        if (!$cities) {
            return ApiResponseHelper::error('City not found.', 404);
        }
        return ApiResponseHelper::success($cities, 'Cities retrieved successfully.');
    }
    #endregion
    #region City
    /**
     * Get a specific city by its code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCity($code) {
        $city = City::with('districts')
            ->where('code', $code)
            ->first();
        if (!$city) {
            return ApiResponseHelper::error('City not found.', 404);
        }
        return ApiResponseHelper::success($city, 'City retrieved successfully.');
    }

    /**
     * Get districts in a specific city.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCityDistrict($code) {
        $districts = City::with('districts')
            ->where('code', $code)
            ->first()
            ->districts;
        if (!$districts) {
            return ApiResponseHelper::error('District not found.', 404);
        }
        return ApiResponseHelper::success($districts, 'Districts retrieved successfully.');
    }
    #endregion
    #region District
    /**
     * Get a specific district by its code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistrict($code) {
        $district = District::with('villages')
            ->where('code', $code)
            ->first();
        if (!$district) {
            return ApiResponseHelper::error('District not found.', 404);
        }
        return ApiResponseHelper::success($district, 'District retrieved successfully.');
    }
    public function getDistrictVillage($code) {
        $villages = District::with('villages')
            ->where('code', $code)
            ->first()->villages;
        if (!$villages) {
            return ApiResponseHelper::error('Village not found.', 404);
        }
        return ApiResponseHelper::success($villages, 'Villages retrieved successfully.');
    }
    #endregion
    #region Village
    public function getVillage($code) {
        $village = Village::where('code', $code)
            ->first();
        if (!$village) {
            return ApiResponseHelper::error('Village not found.', 404);
        }
        return ApiResponseHelper::success($village, 'Village retrieved successfully.');
    }
    #endregion
}
