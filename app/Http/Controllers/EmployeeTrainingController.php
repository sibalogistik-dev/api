<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeTrainingIndexRequest;
use App\Models\EmployeeTraining;
use App\Services\EmployeeTrainingService;
use Exception;
use Illuminate\Http\Request;

class EmployeeTrainingController extends Controller
{
    protected $employeeTrainingService;

    public function __construct(EmployeeTrainingService $employeeTrainingService)
    {
        $this->employeeTrainingService = $employeeTrainingService;
    }

    public function index(EmployeeTrainingIndexRequest $request)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function show(EmployeeTraining $employeeTraining)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function update(Request $request, EmployeeTraining $employeeTraining)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function destroy(EmployeeTraining $employeeTraining)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $th;
        }
    }
}
