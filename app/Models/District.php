<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    protected $fillable = [
        'name',
        'code',
        'city_code',
        'meta'
    ];

    protected $casts = [
        'code'          => 'integer',
        'city_code'     => 'integer',
    ];

    protected $hidden = ['updated_at', 'created_at'];

    public function scopeFilter($query, array $filters)
    {
        // 
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'district_code', 'code');
    }
}
