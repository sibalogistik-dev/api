<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReprimandLetterIndexRequest;
use App\Models\ReprimandLetter;
use App\Services\ReprimandLetterService;
use Illuminate\Http\Request;

class ReprimandLetterController extends Controller
{
    protected ReprimandLetterService $reprimandLetterService;

    public function __construct(ReprimandLetterService $reprimandLetterService)
    {
        $this->reprimandLetterService = $reprimandLetterService;
    }

    public function index(ReprimandLetterIndexRequest $request)
    {
        try {
            $validated  = $request->validated();
            $query      = ReprimandLetter::query();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ReprimandLetter $reprimandLetter)
    {
        //
    }

    public function update(Request $request, ReprimandLetter $reprimandLetter)
    {
        //
    }

    public function destroy(ReprimandLetter $reprimandLetter)
    {
        //
    }
}
