<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $cabang = Cabang::with('kota', 'kota.province')
            ->get();
        return ApiResponseHelper::success('Daftar Cabang', $cabang);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cabang $cabang) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cabang $cabang) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cabang $cabang) {
        //
    }
}
