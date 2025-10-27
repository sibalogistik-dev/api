<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Pendidikan;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pendidikan = Pendidikan::orderBy('id', 'ASC')->get();
        return ApiResponseHelper::success('Daftar Pendidikan', $pendidikan);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Pendidikan $pendidikan)
    {
        //
    }

    public function update(Request $request, Pendidikan $pendidikan)
    {
        //
    }

    public function destroy(Pendidikan $pendidikan)
    {
        //
    }
}
