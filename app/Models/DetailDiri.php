<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDiri extends Model
{
    protected $fillable = [
        'karyawan_id',
        'pas_foto',
        'ktp_foto',
        'sim_foto',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
