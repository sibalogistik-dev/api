<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q']          ?? null, fn($q, $v) => $q->where('name', 'like', "%{$v}%"));
        $query->when($filters['start_date'] ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v));
        $query->when($filters['end_date']   ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v));
    }
}
