<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table =  'provinces';

    protected $fillable = [
        'name',
        'code',
        'meta'
    ];

    protected $casts = [
        'code'  => 'integer',
    ];

    protected $hidden = ['updated_at', 'created_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query
                ->where('name', 'like', "%{$keyword}%")
                ->orWhere('code', 'like', "%{$keyword}%");
        });
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}
