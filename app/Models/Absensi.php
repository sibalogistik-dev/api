<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

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
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('description', 'like', "%{$keyword}%")
                    ->orWhereHas('karyawan', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
            });
        });

        $query->when($filters['date'] ?? null, function ($query, $date) {
            $query->where('date', $date);
        });

        $query->when($filters['branch'] ?? null, function ($query, $branch) {
            if ($branch !== 'all') {
                $query->whereHas('karyawan', function ($query) use ($branch) {
                    $query->where('branch_id', $branch);
                });
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
