<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name'
    ];

    public function employeeDetails()
    {
        return $this->hasMany(DetailDiri::class, 'religion_id');
    }
}
