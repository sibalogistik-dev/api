<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusAbsensi extends Model
{
    protected $fillable = [
        'name',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'attendance_status_id');
    }
}
