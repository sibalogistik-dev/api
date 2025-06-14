<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $fillable = [
        'nama',
        'codename',
    ];

    public function cabangs()
    {
        return $this->hasMany(Cabang::class);
    }
}
