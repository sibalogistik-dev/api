<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusAbsensi extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'attendance_status_id');
    }
}
