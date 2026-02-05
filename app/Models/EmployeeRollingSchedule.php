<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeRollingSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'from_branch_id',
        'to_branch_id',
        'start_date',
        'end_date',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->whereHas('employee', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        });
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }

    public function fromBranch()
    {
        return $this->belongsTo(Cabang::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Cabang::class, 'to_branch_id');
    }
}
