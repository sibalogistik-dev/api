<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'employee_id',
        'attendance_status_id',
        'date',
        'start_time',
        'end_time',
        'attendance_image',
        'description',
        'longitude',
        'latitude',
    ];

    public function statusAbsensi()
    {
        return $this->belongsTo(StatusAbsensi::class, 'attendance_status_id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
