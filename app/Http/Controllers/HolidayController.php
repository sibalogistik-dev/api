<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayIndexRequest;
use App\Models\Holiday;
use App\Services\HolidayService;
use Exception;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    protected $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index(HolidayIndexRequest $request)
    {
        try {
            $validated      = $request->validated();
        } catch (Exception $e) {
            //code...
        }
    }

    public function store(Request $request)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }
    public function show(Holiday $holiday)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function update(Request $request, Holiday $holiday)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function destroy(Holiday $holiday)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }
}
