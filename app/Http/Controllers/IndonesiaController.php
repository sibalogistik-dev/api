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
                return ApiResponseHelper::error('Data Provinsi tidak ditemukan.', null, 404);
            }
        }
        return ApiResponseHelper::success('Data Provinsi berhasil diambil.', $province);
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
            return ApiResponseHelper::error('Data Kota tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Data Kota berhasil diambil.', $cities);
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
            return ApiResponseHelper::error('Data Kota tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Data Kota berhasil diambil.', $city);
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
            return ApiResponseHelper::error('Data Kecamatan tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Data Kecamatan berhasil diambil.', $districts);
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
            return ApiResponseHelper::error('Data Kecamatan tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Data Kecamatan berhasil diambil.', $district);
    }

    /**
     * Get villages in a specific district.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistrictVillage($code) {
        $villages = District::with('villages')
            ->where('code', $code)
            ->first()->villages;
        if (!$villages) {
            return ApiResponseHelper::error('Data Kelurahan tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Data Kelurahan berhasil diambil.', $villages);
    }
    #endregion
    #region Village
    /**
     * Get a specific village by its code.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVillage($code) {
        $village = Village::where('code', $code)
            ->first();
        if (!$village) {
            return ApiResponseHelper::error('Data Kelurahan tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Data Kelurahan berhasil diambil.', $village);
    }
    #endregion
}
