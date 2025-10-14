<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\Provinsi;

class ProvinsiController extends Controller
{
    public function index(Request $request)
    {
        $provinsis = Provinsi::query()
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(
                $request->paginate,
                fn($query) => $query->paginate($request->perPage ?? 10),
                fn($query) => $query->get()
            );

        if ($provinsis->isEmpty()) {
            return ApiResponseHelper::error('Provinsi tidak ditemukan.', null, 404);
        }

        return ApiResponseHelper::success('Provinsi berhasil diambil.', $provinsis);
    }

    public function store(Request $request)
    {
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

    public function show($provinsi)
    {
        $province = Provinsi::with('cities')
            ->where('code', $provinsi)
            ->first();
        if (!$provinsi) {
            return ApiResponseHelper::error('Provinsi tidak ditemukan.', null, 404);
        }
        return ApiResponseHelper::success('Provinsi berhasil diambil.', $province);
    }

    public function update(Request $request, Provinsi $provinsi)
    {
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

    public function destroy(Provinsi $provinsi)
    {
        if (!$provinsi) {
            return ApiResponseHelper::error('Provinsi tidak ditemukan.', null, 404);
        }
        $provinsi->delete();
        return ApiResponseHelper::success('Provinsi berhasil dihapus.', null, 204);
    }
}
