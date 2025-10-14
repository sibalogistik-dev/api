<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Absensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function index(Request $request)
    {
        $keyword    = $request->input('q');
        $tanggal    = $request->input('date', date('Y-m-d'));
        $cabang     = $request->input('branch', 'all');
        $paginate   = $request->boolean('paginate', false);
        $perPage    = $request->integer('perPage', 10);
        $user       = $request->user()->load('permissions', 'karyawan');

        $query = Absensi::query();

        $absensi = $query
            ->where('date', $tanggal)
            ->with([
                'karyawan.cabang.perusahaan',
                'statusAbsensi'
            ])
            ->orderBy('id', 'DESC')
            ->when($cabang !== 'all', function ($q) use ($cabang) {
                $q->whereHas('karyawan', function ($w) use ($cabang) {
                    $w->where('branch_id', $cabang);
                });
            })
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($w) use ($keyword) {
                    $w->where('description', 'like', "%$keyword%")
                        ->orWhereHas('karyawan', function ($e) use ($keyword) {
                            $e->where('name', 'like', "%$keyword%");
                        });
                });
            })
            ->when(
                !$user->hasPermissionTo('hrd app') && !$user->hasPermissionTo('finance app'),
                fn($query) => $query->where('employee_id', $user->karyawan['id'])
            )
            ->when(
                $paginate,
                fn($query) => $query->paginate($perPage),
                fn($query) => $query->get()
            );

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
            'attendance_image'      => ['required', 'image', 'mimes:jpg,png,webp', 'max:2048'],
            'description'           => ['nullable', 'string', 'max:255'],
            'longitude'             => ['required', 'float'],
            'latitude'              => ['required', 'float'],
            'attendance_type'       => ['required', 'string', 'in:masuk,pulang'],
        ]);

        if ($validate->fails()) {
            return ApiResponseHelper::error('Validasi data gagal!', $validate->errors(), 422);
        }

        $attendance_type = $request->input('attendance_type');
        DB::beginTransaction();

        try {
            $data = $request->except('attendance_type');

            if ($attendance_type === 'masuk') {
                $data['attendance_image'] = $request->file('attendance_image')->store('uploads/attendance_image', 'public');
                $data['date'] = date('Y-m-d');
                $data['start_time'] = date('H:i:s');
                $absensi = Absensi::create([]);
            } elseif ($attendance_type === 'pulang') {
                # code...
            }
        } catch (Exception $e) {
            //throw $th;
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
