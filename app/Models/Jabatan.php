<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name'
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'job_title_id');
    }
}
