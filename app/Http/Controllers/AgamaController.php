<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ReligionIndexRequest;
use App\Http\Requests\ReligionStoreRequest;
use App\Http\Requests\ReligionUpdateRequest;
use App\Models\Agama;
use Illuminate\Http\Request;

class AgamaController extends Controller
{
    public function index(ReligionIndexRequest $request)
    {
        $validated = $request->validated();
        $religionQuery = Agama::query()->filter($validated)->orderBy('id');
        $religion = isset($validated['paginate']) && $validated['paginate'] ? $religionQuery->paginate($validated['perPage'] ?? 10) : $religionQuery->get();
        return ApiResponseHelper::success('Religion list', $religion);
    }

    public function create()
    {
        //
    }

    public function store(ReligionStoreRequest $request)
    {
        //
    }

    public function show($religion)
    {
        $agama = Agama::find($religion);
        if (!$agama) {
            return ApiResponseHelper::error('Religion not found', [], 404);
        }
        return ApiResponseHelper::success('Religion detail', $agama);
    }

    public function edit(Agama $religion)
    {
        //
    }

    public function update(ReligionUpdateRequest $request, $religion)
    {
        //
    }

    public function destroy($religion)
    {
        $agama = Agama::find($religion);
        if (!$agama) {
            return ApiResponseHelper::error('Religion not found', [], 404);
        }
        $delete = $agama->delete();
        if ($delete) {
            return ApiResponseHelper::success('Religion data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Religion data failed to delete', null, 500);
        }
    }
}
