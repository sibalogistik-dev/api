<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'employee_id'           => 'integer',
        'attendance_status_id'  => 'integer',
        'half_day'              => 'boolean',
        'sick_note'             => 'boolean',
        'late_arrival_time'     => 'integer',
    ];

    protected $fillable = [
        'employee_id',
        'attendance_status_id',
        'date',
        'start_time',
        'end_time',
        'attendance_image',
        'description',
        'longitude',
        'latitude',
        'half_day',
        'sick_note',
        'late_arrival_time',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('description', 'like', "%{$keyword}%")
                    ->orWhereHas('employee', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
            });
        });

        $query->when($filters['date'] ?? null, function ($query, $date) {
            $query->where('date', $date);
        });

        $query->when($filters['branch'] ?? null, function ($query, $branch) {
            if ($branch !== 'all') {
                $query->whereHas('employee', function ($query) use ($branch) {
                    $query->where('branch_id', $branch);
                });
            }
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            if ($status !== 'all' || $status !== '') {
                $query->where('attendance_status_id', $status);
            }
        });
    }

    public function attendanceStatus()
    {
        return $this->belongsTo(StatusAbsensi::class, 'attendance_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
