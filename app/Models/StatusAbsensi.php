<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusAbsensi extends Model
{
    protected $fillable = [
        'nama',
    ];

    public $timestamps = true;

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'status_id');
    }
}
