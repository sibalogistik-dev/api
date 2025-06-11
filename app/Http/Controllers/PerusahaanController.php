<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;

class PerusahaanController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        if ($request->has('search')) {
            $search = $request->search;
            $perusahaan = Perusahaan::where('nama', 'like', '%' . $search . '%')
                ->with('cabangs', 'cabangs.kota', 'cabangs.kota.province')
                ->get();
        } else {
            $perusahaan = Perusahaan::with('cabangs', 'cabangs.kota', 'cabangs.kota.province')
                ->get();
        }
        return ApiResponseHelper::success(
            $perusahaan,
            'Daftar Perusahaan',
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perusahaan  $perusahaan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Perusahaan $perusahaan) {
        $perusahaan = Perusahaan::with('cabangs', 'cabangs.kota', 'cabangs.kota.province')
            ->find($perusahaan->id);
        if (!$perusahaan) {
            return ApiResponseHelper::error(
                'Perusahaan tidak ditemukan',
                404,
                null,
            );
        }
        return ApiResponseHelper::success(
            $perusahaan,
            'Detail Perusahaan',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perusahaan $perusahaan) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perusahaan $perusahaan) {
        //
    }

    /**
     * Get the list of cabangs for SIBA Cargo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cabangSiba() {
        $perusahaan = Perusahaan::where('id', 1)
            ->with(['cabangs' => function ($query) {
                $query->with('kota', 'kota.province');
            }])
            ->first();

        if (!$perusahaan) {
            return response()->json([
                'status' => false,
                'message' => 'Perusahaan Siba Cargo not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'List of Cabang Siba Cargo',
            'data' => $perusahaan->cabangs
        ], 200);
    }

    /**
     * Get the list of cabangs for Best Furniture.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cabangBest() {
        $perusahaan = Perusahaan::where('id', 2)
            ->with(['cabangs' => function ($query) {
                $query->with('kota', 'kota.province');
            }])
            ->first();

        if (!$perusahaan) {
            return response()->json([
                'status' => false,
                'message' => 'Perusahaan Best Furniture not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'List of Cabang Best Furniture',
            'data' => $perusahaan->cabangs
        ], 200);
    }

    /**
     * Get the list of cabangs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cabangMenCargo() {
        $perusahaan = Perusahaan::where('id', 3)
            ->with(['cabangs' => function ($query) {
                $query->with('kota', 'kota.province');
            }])
            ->first();

        if (!$perusahaan) {
            return response()->json([
                'status' => false,
                'message' => 'Perusahaan Men Cargo not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'List of Cabang Men Cargo',
            'data' => $perusahaan->cabangs
        ], 200);
    }

    /**
     * Get the list of cabangs for Mabes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cabangMabes() {
        $perusahaan = Perusahaan::where('id', 4)
            ->with(['cabangs' => function ($query) {
                $query->with('kota', 'kota.province');
            }])
            ->first();

        if (!$perusahaan) {
            return response()->json([
                'status' => false,
                'message' => 'Perusahaan Mabes not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'List of Cabang Mabes',
            'data' => $perusahaan->cabangs
        ], 200);
    }

    /**
     * Get the list of cabangs for SAuto8.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cabangSauto8() {
        $perusahaan = Perusahaan::where('id', 5)
            ->with(['cabangs' => function ($query) {
                $query->with('kota', 'kota.province');
            }])
            ->first();

        if (!$perusahaan) {
            return response()->json([
                'status' => false,
                'message' => 'Perusahaan SAuto8 not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'List of Cabang SAuto8',
            'data' => $perusahaan->cabangs
        ], 200);
    }
}
