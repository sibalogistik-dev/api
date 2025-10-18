<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name'
    ];

    public function employee()
    {
        return $this->hasMany(Karyawan::class, 'education_id');
    }
}
