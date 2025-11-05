<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\PayrollStoreRequest;
use App\Models\Payroll;
use App\Services\PayrollService;
use Exception;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        //
    }

    public function store(PayrollStoreRequest $request)
    {
        try {
            $payroll = $this->payrollService->create($request->validated());
            return ApiResponseHelper::success('Payroll data has been added successfully', $payroll);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving payroll data', $e->getMessage(), 500);
        }
    }

    public function show(Payroll $payroll)
    {
        //
    }

    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    public function destroy(Payroll $payroll)
    {
        //
    }
}
