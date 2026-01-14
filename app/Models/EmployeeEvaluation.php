<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeEvaluation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'evaluation_date',
        'description',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->whereHas('employee', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            })->orWhere('description', 'like', '%' . $keyword . '%');
        });

        $query->when($filters['employee_id'] ?? null, fn($q, $v) => $q->where('employee_id', $v));
        $query->when($filters['evaluator_id'] ?? null, fn($q, $v) => $q->where('evaluator_id', $v));
        $query->when($filters['start_date'] ?? null, fn($q, $v) => $q->whereDate('evaluation_date', '>=', $v));
        $query->when($filters['end_date'] ?? null, fn($q, $v) => $q->whereDate('evaluation_date', '<=', $v));
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }

    public function evaluator()
    {
        return $this->belongsTo(Karyawan::class, 'evaluator_id')->withTrashed();
    }
}
