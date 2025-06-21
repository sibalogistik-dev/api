<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGaji extends Model
{
    protected $fillable = [
        'karyawan_id',
        'no_rekening',
        'status_gaji',
        'gaji_bulanan',
        'gaji_harian',
        'uang_makan',
        'bonus',
        'tunjangan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
