<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MiddayAttendance extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'employee_id',
        'date_time',
        'longitude',
        'latitude',
        'image',
        'description',
        'late_arrival_time'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('description', 'like', "%{$keyword}%")
                ->orWhereHas('employee', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
        });

        $query->when($filters['employee_id'] ?? null, function ($query, $employeeId) {
            $query->where('employee_id', $employeeId);
        });

        $query->when($filters['date'] ?? null, function ($query, $date) {
            $query->where('date_time', $date);
        });

        $query->when($filters['branch'] ?? null, function ($query, $branch) {
            if ($branch !== 'all') {
                $query->whereHas('employee', function ($query) use ($branch) {
                    $query->where('branch_id', $branch);
                });
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }
}
