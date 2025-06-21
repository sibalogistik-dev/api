<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Cabang;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $codename = $request->input('codename', 'semua');
        $keyword = $request->input('q');
        $perPage = $request->input('perPage', 5);
        $combobox = $request->input('combobox', 0);

        $query = Cabang::with(['kota.province', 'perusahaan']);

        if ($codename !== 'semua') {
            $perusahaan = Perusahaan::where('codename', $codename)->first();
            if (!$perusahaan) {
                return ApiResponseHelper::error("Perusahaan dengan codename '{$codename}' tidak ditemukan", null, 404);
            }

            $query->where('perusahaan_id', $perusahaan->id);
        }

        if ($keyword) {
            if ($combobox == 1) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            } else {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('alamat', 'like', "%{$keyword}%")
                        ->orWhereHas('kota', function ($qKota) use ($keyword) {
                            $qKota->where('name', 'like', "%{$keyword}%");
                        });
                });
                # code...
            }
        }

        $cabangs = $query->orderBy('perusahaan_id', 'asc')->paginate($perPage);

        $title = $codename === 'semua'
            ? 'Daftar Semua Cabang Dari Semua Perusahaan'
            : "Daftar Cabang {$perusahaan->nama}";

        return ApiResponseHelper::success($title, $cabangs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cabang $cabang)
    {
        return ApiResponseHelper::success('Detail Data Cabang', $cabang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cabang $cabang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cabang $cabang)
    {
        //
    }
}
