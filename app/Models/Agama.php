<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $fillable = [
        'name'
    ];

    public function DetailDiri()  {
        return $this->hasMany(DetailDiri::class, 'religion_id');
    }
}
