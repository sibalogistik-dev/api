<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'villages';

    protected $fillable = [
        'name',
        'code',
        'district_code',
        'meta'
    ];

    protected $casts = [
        'code'          => 'integer',
        'district_code' => 'integer',
    ];

    protected $hidden = ['updated_at', 'created_at'];

    public function scopeFilter($query, array $filters)
    {
        // 
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
