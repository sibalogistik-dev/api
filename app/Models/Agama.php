<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agama extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name'
    ];

    public function employeeDetails()
    {
        return $this->hasMany(DetailDiri::class, 'religion_id');
    }
}
