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
        $city = City::where('province_code', $code)->get();
        if (!$city) {
            return ApiResponseHelper::error('City not found.', 404);
        }
        return ApiResponseHelper::success($city, 'City retrieved successfully.');
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
    public function getCityDistrict($code) {
        $city = City::with('districts')
            ->where('code', $code)
            ->first();
        if (!$city) {
            return ApiResponseHelper::error('City not found.', 404);
        }
        return ApiResponseHelper::success($city, 'City retrieved successfully.');
    }
    #endregion
    #region District
    public function getDistrict($code) {
        $district = District::with('villages')
            ->where('code', $code)
            ->first();
        if (!$district) {
            return ApiResponseHelper::error('District not found.', 404);
        }
        return ApiResponseHelper::success($district, 'District retrieved successfully.');
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
