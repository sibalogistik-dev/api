<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'min_kpi'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%{$v}%"));
    }

    public function employee()
    {
        return $this->hasMany(Karyawan::class, 'job_title_id')->withTrashed();
    }

    public function jobDescriptions()
    {
        return $this->hasMany(JobDescription::class, 'job_title_id');
    }
}
