<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $perPage = $request->input('perPage', 5);

        $query = Karyawan::query();

        if ($keyword) {
            $query->where('nama', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('jabatan', function ($q) use ($keyword) {
                    $q->where('nama', 'LIKE', '%' . $keyword . '%');
                })
                ->orWhereHas('cabang', function ($q) use ($keyword) {
                    $q->where('nama', 'LIKE', '%' . $keyword . '%');
                })
            ;
        }

        $karyawan = $query->with('cabang', 'jabatan')->orderBy('id', 'ASC')->paginate($perPage);
        return ApiResponseHelper::success('Daftar Karyawan', $karyawan);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        //
    }
}
