<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $fillable = [
        'name'
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'education_id');
    }
}
