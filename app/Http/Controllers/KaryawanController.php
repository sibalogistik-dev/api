<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\IndexKaryawanRequest;
use App\Http\Requests\StoreKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;
use App\Models\Karyawan;
use App\Services\KaryawanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Json;

class KaryawanController extends Controller
{
    protected $karyawanService;

    public function __construct(KaryawanService $karyawanService)
    {
        $this->karyawanService = $karyawanService;
    }

    public function index(IndexKaryawanRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user()->load('employee');

        $karyawanQuery = Karyawan::query()
            ->with([
                'jobTitle',
                'branch.city:code,name',
                'employeeDetails.religion',
                'employeeDetails.birthPlace:code,name',
                'employeeDetails.education',
                'employeeDetails.residentialArea:code,name',
                'employeeDetails.marriageStatus',
                'salaryDetails',
                'salaryHistory',
            ])
            ->filter($validated)
            ->when(!($validated['getAll'] ?? false), function ($query) use ($user) {
                if (isset($user->employee)) {
                    $query->where('id', $user->employee->id);
                }
            })
            ->orderBy('id', 'desc');

        $karyawan = isset($validated['paginate']) && $validated['paginate'] ? $karyawanQuery->paginate($validated['perPage'] ?? 10) : $karyawanQuery->get();

        return ApiResponseHelper::success('Daftar Karyawan', $karyawan);
    }

    public function create()
    {
        //
    }

    public function store(StoreKaryawanRequest $request)
    {
        try {
            $this->karyawanService->createEmployee($request->validated());
            return ApiResponseHelper::success('Data karyawan berhasil ditambahkan');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Terjadi kesalahan saat menyimpan data', $e->getMessage(), 500);
        }
    }

    public function show(Karyawan $karyawan)
    {
        $data = Karyawan::with([
            'employeeDetails.religion',
            'employeeDetails.birthPlace:code,name',
            'employeeDetails.education',
            'employeeDetails.residentialArea:code,name',
            'employeeDetails.marriageStatus',
            'jobTitle',
            'attendance.attendanceStatus',
        ])->withTrashed()->find($karyawan->id);
        return ApiResponseHelper::success('Detail Data karyawan', $data);
    }

    public function update(Karyawan $karyawan, UpdateKaryawanRequest $request)
    {
        try {
            $this->karyawanService->updateEmployee($karyawan, $request->validated());
            return ApiResponseHelper::success('Data karyawan berhasil diubah');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Terjadi kesalahan saat memperbarui data', $e->getMessage(), 500);
        }
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->user->delete();
        $delete = $karyawan->delete();
        if ($delete) {
            return ApiResponseHelper::success('Data karyawan berhasil dinon-aktifkan');
        } else {
            return ApiResponseHelper::error('Data karyawan gagal dinon-aktifkan');
        }
    }
}
