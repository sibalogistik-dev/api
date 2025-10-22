<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use SoftDeletes;

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
