<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table =  'provinces';

    protected $hidden = ['updated_at', 'created_at'];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}
