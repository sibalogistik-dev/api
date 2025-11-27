<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Overtime extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'employee_id',
        'start_time',
        'end_time',
        'approved',
        'description',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->whereHas('employee', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        });
        $query->when($filters['start_date'] ?? null, function ($query, $startDate) {
            $query->whereDate('start_time', '>=', $startDate);
        });
        $query->when($filters['end_date'] ?? null, function ($query, $endDate) {
            $query->whereDate('end_time', '<=', $endDate);
        });
        $query->when($filters['employee_id'] ?? null, function ($query, $employeeId) {
            $query->where('employee_id', $employeeId);
        });
        $query->when($filters['approved'] ?? null, function ($query, $approved) {
            if ($approved !== 'all') {
                $query->where('approved', $approved);
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }
}
