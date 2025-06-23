<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Models\DetailDiri;
use App\Models\DetailGaji;
use App\Models\Karyawan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        $validator = Validator::make($request->all(), [
            // Akun
            'username'          => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'          => ['required', 'string', 'min:8'],
            // Data Pegawai
            'nama'              => ['required', 'string', 'max:255'],
            'npk'               => ['required', 'string', 'max:50', 'unique:karyawans,npk'],
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
            'pas_foto'          => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'ktp_foto'          => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'sim_foto'          => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            // Gaji
            'status_gaji'       => ['required', 'in:harian,bulanan'],
            'gaji_harian'       => ['required', 'numeric', 'min:0'],
            'gaji_bulanan'      => ['required', 'numeric', 'min:0'],
            'uang_makan'        => ['required', 'numeric', 'min:0'],
            'bonus'             => ['required', 'numeric', 'min:0'],
            'tunjangan'         => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            Log::error('Validasi gagal:', $validator->errors()->toArray());
            return ApiResponseHelper::error('Validasi data gagal', $validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verify_at' => now(),
                'user_type' => 'employee',
            ]);

            $user->givePermissionTo('absensi app');

            $karyawan = Karyawan::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'npk' => $request->npk,
                'jabatan_id' => $request->jabatan_id,
                'cabang_id' => $request->cabang_id,
                'tanggal_masuk' => $request->tanggal_masuk,
            ]);

            $pasFotoPath = $request->file('pas_foto')->store('uploads/pas_foto', 'public');
            $ktpFotoPath = $request->file('ktp_foto')->store('uploads/ktp_foto', 'public');
            $simFotoPath = $request->file('sim_foto') ? $request->file('sim_foto')->store('uploads/sim_foto', 'public') : null;

            DetailDiri::create([
                'karyawan_id' => $karyawan->id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama_id' => $request->agama_id,
                'no_telp' => $request->no_telp,
                'tempat_lahir_id' => $request->tempat_lahir_id,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'golongan_darah' => $request->golongan_darah,
                'pendidikan_id' => $request->pendidikan_id,
                'status_kawin' => $request->status_kawin,
                'daerah_tinggal_id' => $request->daerah_tinggal_id,
                'pas_foto' => $pasFotoPath,
                'ktp_foto' => $ktpFotoPath,
                'sim_foto' => $simFotoPath,
            ]);

            DetailGaji::create([
                'karyawan_id' => $karyawan->id,
                'status_gaji' => $request->status_gaji,
                'gaji_harian' => $request->gaji_harian,
                'gaji_bulanan' => $request->gaji_bulanan,
                'uang_makan' => $request->uang_makan,
                'bonus' => $request->bonus,
                'tunjangan' => $request->tunjangan,
            ]);

            DB::commit();
            return ApiResponseHelper::success('Data karyawan berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return ApiResponseHelper::error('Terjadi kesalahan saat menyimpan data', $e->getMessage(), 500);
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
