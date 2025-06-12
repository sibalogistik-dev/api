<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\Provinsi;

class ProvinsiController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        // Fetch all provinces
        if ($request->has('search')) {
            $search = $request->search;
            $provinsis = Provinsi::where('name', 'like', '%' . $search . '%')
                ->get();
        } else {
            $provinsis = Provinsi::all();
        }
        if ($provinsis->isEmpty()) {
            return ApiResponseHelper::error('Provinsi tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Provinsi berhasil diambil.', $provinsis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:indonesia_provinces,code',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ]);
        if ($validate->fails()) {
            return ApiResponseHelper::error('Validasi gagal.', $validate->errors(), 422);
        } else {
            $provinsi = Provinsi::create([
                'name' => $request->name,
                'code' => $request->code,
                'meta' => [
                    'lat' => $request->lat,
                    'long' => $request->long
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if (!$provinsi) {
                return ApiResponseHelper::error('Gagal membuat provinsi.', null, 500);
            }
        }
        return ApiResponseHelper::success('Provinsi berhasil dibuat.', $provinsi, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Provinsi $provinsi
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Provinsi $provinsi) {
        $provinsi = Provinsi::with('cities')
            ->where('code', $provinsi->code)
            ->first();
        if (!$provinsi) {
            return ApiResponseHelper::error('Provinsi tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Provinsi berhasil diambil.', $provinsi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Provinsi $provinsi
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Provinsi $provinsi) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:indonesia_provinces,code,' . $provinsi->id,
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ]);
        if ($validate->fails()) {
            return ApiResponseHelper::error('Validasi gagal.', $validate->errors(), 422);
        } else {
            $provinsi->update([
                'name' => $request->name,
                'code' => $request->code,
                'meta' => [
                    'lat' => $request->lat,
                    'long' => $request->long
                ],
                'updated_at' => now(),
            ]);
        }
        return ApiResponseHelper::success('Provinsi berhasil diperbarui.', $provinsi);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Provinsi $provinsi
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Provinsi $provinsi) {
        if (!$provinsi) {
            return ApiResponseHelper::error('Provinsi tidak ditemukan.', null, 404);
        }
        $provinsi->delete();
        return ApiResponseHelper::success('Provinsi berhasil dihapus.', null, 204);
    }
}
