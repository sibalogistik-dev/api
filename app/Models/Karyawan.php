<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class Karyawan extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'npk',
        'jabatan_id',
        'cabang_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'kontrak',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function detail_diri()
    {
        return $this->hasOne(DetailDiri::class);
    }

    public function detail_gaji()
    {
        return $this->hasOne(DetailGaji::class);
    }
}
