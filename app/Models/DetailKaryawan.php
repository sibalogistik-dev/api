<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class DetailKaryawan extends Model
{
    protected $fillable = [
        'karyawan_id',
        'nik',
        'jabatan_id',
        'cabang_id',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function daerah_tinggal()
    {
        return $this->belongsTo(City::class, 'daerah_tinggal_id', 'code');
    }
}
