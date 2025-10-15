<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\IndexAbsensiRequest;
use App\Models\Absensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * params {Request} $request 
     * params {string}  $request->date      (important)
     * params {string}  $request->keyword   (optional)
     * params {string}  $request->branch    (optional)
     * params {int}     $request->perPage   (optional)
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexAbsensiRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user()->load('karyawan');

        $absensiQuery = Absensi::query()
            ->with(['karyawan:id,name,branch_id', 'karyawan.cabang:id,name', 'statusAbsensi:id,name'])
            ->filter($validated)
            ->when(!$user->hasAnyPermission(['hrd app', 'finance app']), fn($query) => $query->where('employee_id', $user->karyawan->id))
            ->latest();

        $absensi = isset($validated['paginate']) && $validated['paginate'] ? $absensiQuery->paginate($validated['perPage'] ?? 10) : $absensiQuery->get();

        return ApiResponseHelper::success('Daftar Absensi', $absensi);
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
        $validate = Validator::make($request->all(), [
            'employee_id'           => ['required', 'integer', 'exists:karyawans,id'],
            'attendance_status_id'  => ['required', 'integer', 'exists:status_absensis,id'],
            'description'           => ['required', 'string', 'max:255'],
            'longitude'             => ['required', 'decimal:1,10'],
            'latitude'              => ['required', 'decimal:1,10'],
            'attendance_type'       => ['required', 'string', 'in:masuk,pulang'],
        ]);

        if ($validate->fails()) {
            return ApiResponseHelper::error('Validasi data gagal!', $validate->errors(), 422);
        }

        $attendance_type = $request->input('attendance_type');
        DB::beginTransaction();

        try {
            $dataAbsensi = $request->except('attendance_type');

            if ($attendance_type === 'masuk') {
                $dataAbsensi['date'] = date('Y-m-d');
                $dataAbsensi['start_time'] = date('H:i:s');
                $absensi = Absensi::create($dataAbsensi);
                if ($absensi) {
                    DB::commit();
                    return ApiResponseHelper::success('Data Absensi berhasil ditambahkan');
                }
                // return ApiResponseHelper::success('Data Absensi berhasil ditambahkan', $dataAbsensi);
            } elseif ($attendance_type === 'pulang') {
                # code...
            }
        } catch (Exception $e) {
            DB::rollback();
            return ApiResponseHelper::error('Terjadi kesalahan saat menyimpan data', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        $absensi->load(
            'karyawan.jabatan',
            'karyawan.cabang.perusahaan',
            'statusAbsensi'
        );
        return ApiResponseHelper::success('Data Absensi', $absensi);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}
