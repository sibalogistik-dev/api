<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\City;
use App\Models\KotaKab;
use Illuminate\Http\Request;

class KotaKabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $perPage = $request->input('perPage', 5);
        $query = City::query();

        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        $kotakab = $query
            ->orderBy('id', 'ASC')
            ->paginate($perPage);
        return ApiResponseHelper::success('Daftar Kota', $kotakab);
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
    public function show(City $kotaKab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $kotaKab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $kotaKab)
    {
        //
    }
}
