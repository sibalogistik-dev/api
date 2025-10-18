<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarriageStatus extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name'
    ];

    public function detailDiri()
    {
        return $this->hasMany(DetailDiri::class);
    }
}
