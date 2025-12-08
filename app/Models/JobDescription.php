<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDescription extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'job_title_id',
        'task_name',
        'task_detail',
        'priority_level',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query
                ->where('task_name', 'like', '%' . $keyword . '%')
                ->orWhere('task_detail', 'like', '%' . $keyword . '%');
        });
        $query->when($filters['job_title_id'] ?? null, function ($query, $job_title_id) {
            $query->where('job_title_id', $job_title_id);
        });
        $query->when($filters['priority_level'] ?? null, function ($query, $priority_level) {
            if ($priority_level !== 'all') {
                $query->where('priority_level', $priority_level);
            }
        });
    }

    public function jobTitle()
    {
        return $this->belongsTo(Jabatan::class, 'job_title_id');
    }
}
