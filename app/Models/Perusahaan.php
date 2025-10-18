<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name',
        'codename',
    ];

    public function branches()
    {
        return $this->hasMany(Cabang::class, 'company_id');
    }
}
