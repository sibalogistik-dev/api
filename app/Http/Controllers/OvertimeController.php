<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    protected $overtimeService;

    public function __construct(OvertimeService $overtimeService)
    {
        $this->overtimeService = $overtimeService;
    }

    public function index()
    {
        $data = Overtime::get();
        return ApiResponseHelper::success('Daftar Overtime', $data);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Overtime $overtime)
    {
        //
    }

    public function update(Request $request, Overtime $overtime)
    {
        //
    }

    public function destroy(Overtime $overtime)
    {
        //
    }
}
