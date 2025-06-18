<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'nama',
        'user_id',
        'cabang_id',
        'jabatan_id',
        'nik',
        'no_telepon',
        'alamat',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
