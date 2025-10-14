<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Agama;
use Illuminate\Http\Request;

class AgamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword    = $request->input('q');
        $paginate   = $request->boolean('paginate', false);

        $query = Agama::query();

        $cabangs = $query
            ->orderBy('id', 'asc')
            ->when(
                $keyword,
                fn($query) => $query->where('name', 'like', '%' . $keyword . '%'),
            )
            ->when(
                $paginate,
                fn($query) => $query->paginate(10),
                fn($query) => $query->get()
            );

        $title = 'Daftar Agama';

        return ApiResponseHelper::success($title, $cabangs);
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
    public function show(Agama $agama)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agama $agama)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agama $agama)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agama $agama)
    {
        //
    }
}
