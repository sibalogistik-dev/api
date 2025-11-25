<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusAbsensi extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'name',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        });
    }

    public function attendances()
    {
        return $this->hasMany(Absensi::class, 'attendance_status_id');
    }
}
