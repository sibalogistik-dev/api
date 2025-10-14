<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $fillable = [
        'name'
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'job_title_id');
    }
}
