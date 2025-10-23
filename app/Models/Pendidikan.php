<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendidikan extends Model
{
    use SoftDeletes;
    
    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'name'
    ];

    public function employee()
    {
        return $this->hasMany(Karyawan::class, 'education_id');
    }
}
