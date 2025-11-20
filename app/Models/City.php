<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'code',
        'province_code',
        'meta'
    ];

    protected $casts = [
        'code'          => 'integer',
        'province_code' => 'integer',
    ];

    protected $hidden = ['updated_at', 'created_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('code', 'like', "%{$keyword}%");
        });
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_code', 'code');
    }
}
