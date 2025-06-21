<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $cabang = $request->input('cabang');
        $perPage = $request->input('perPage', 5);

        $query = Karyawan::query();

        if ($cabang !== 'semua') {
            $query->where('cabang_id',  $cabang);
        }

        if ($keyword) {
            $query
                ->where('nama', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('jabatan', function ($q) use ($keyword) {
                    $q->where('nama', 'LIKE', '%' . $keyword . '%');
                });
        }
        $karyawan = $query
            ->with(
                'jabatan',
                'cabang',
                'detail_diri',
                'detail_diri.agama',
                'detail_diri.tempat_lahir',
                'detail_diri.pendidikan',
                'detail_diri.daerah_tinggal',
                'detail_gaji',
            )
            ->orderBy('id', 'ASC')
            ->paginate($perPage);
        return ApiResponseHelper::success('Daftar Karyawan', $karyawan);
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
            // Akun
            'username'          => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'          => ['required', 'string', 'min:8'],

            // Data Pegawai
            'nama'              => ['required', 'string', 'max:255'],
            'npk'               => ['required', 'string', 'max:50', 'unique:karyawan,npk'],
            'jabatan_id'        => ['required', 'integer', 'exists:jabatans,id'],
            'cabang_id'         => ['required', 'integer', 'exists:cabangs,id'],
            'tanggal_masuk'     => ['required', 'date'],
            // Data Diri
            'jenis_kelamin'     => ['required', 'in:laki-laki,perempuan'],
            'agama_id'          => ['required', 'integer', 'exists:agamas,id'],
            'no_telp'           => ['required', 'string', 'max:20'],
            'tempat_lahir_id'   => ['required', 'integer', 'exists:indonesia_cities,code'],
            'tanggal_lahir'     => ['required', 'date'],
            'alamat'            => ['required', 'string'],
            'golongan_darah'    => ['nullable', 'in:a,b,ab,o,none'],
            'pendidikan_id'     => ['required', 'integer', 'exists:pendidikans,id'],
            'status_kawin'      => ['required', 'in:belum kawin,kawin,duda,janda'],
            'daerah_tinggal_id' => ['required', 'integer', 'exists:indonesia_cities,code'],
            // gambar
            'pas_foto'          => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'ktp_foto'          => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'sim_foto'          => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            // Gaji
            'status_gaji'       => ['required', 'in:harian,bulanan'],
            'gaji_harian'       => ['nullable', 'numeric', 'min:0'],
            'gaji_bulanan'      => ['nullable', 'numeric', 'min:0'],
            'uang_makan'        => ['nullable', 'numeric', 'min:0'],
            'bonus'             => ['nullable', 'numeric', 'min:0'],
            'tunjangan'         => ['nullable', 'numeric', 'min:0'],
        ]);

        if (!$validate) {
            return ApiResponseHelper::error('Validasi data gagal', $validate->errors(), 422);
        } else {
            return ApiResponseHelper::success('sukses', $request->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        //
    }
}
