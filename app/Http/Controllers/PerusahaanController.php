<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Models\Cabang;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $search = $request->search;
            $perusahaan = Perusahaan::where('nama', 'like', '%' . $search . '%')
                ->with('cabangs', 'cabangs.kota', 'cabangs.kota.province')
                ->get();
        } else {
            $perusahaan = Perusahaan::with('cabangs', 'cabangs.kota', 'cabangs.kota.province')
                ->get();
        }
        return ApiResponseHelper::success('Daftar Perusahaan', $perusahaan);
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
     *
     * @param  \App\Models\Perusahaan  $perusahaan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Perusahaan $perusahaan)
    {
        $perusahaan = Perusahaan::with('cabangs', 'cabangs.kota', 'cabangs.kota.province')
            ->find($perusahaan->id);
        if (!$perusahaan) {
            return ApiResponseHelper::error('Perusahaan tidak ditemukan', null, 404);
        }
        return ApiResponseHelper::success('Detail Perusahaan', $perusahaan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perusahaan $perusahaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perusahaan $perusahaan)
    {
        //
    }

    /**
     * Display all branches.
     *
     * @param  String  $codename
     * @return \Illuminate\Http\JsonResponse
     */
    public function cabangByCodename($codename, Request $request)
    {
        $query = Cabang::with(['kota.province', 'perusahaan']);
        $keyword = $request->input('q');

        if ($codename !== 'semua') {
            $perusahaan = Perusahaan::where('codename', $codename)->first();

            if (!$perusahaan) {
                return ApiResponseHelper::error("Perusahaan {$codename} tidak ditemukan", null, 404);
            }

            $query->where('perusahaan_id', $perusahaan->id);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('alamat', 'like', '%' . $keyword . '%')
                    ->orWhereHas('kota', function ($kotaQuery) use ($keyword) {
                        $kotaQuery->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $perPage = $request->perPage ?? 5;
        $cabangs = $query->orderBy('perusahaan_id', 'asc')->paginate($perPage);
        $title = $codename === 'semua'
            ? 'Daftar Semua Cabang Perusahaan'
            : "Daftar Cabang {$perusahaan->nama}";

        return ApiResponseHelper::success($title, $cabangs);
    }
}
