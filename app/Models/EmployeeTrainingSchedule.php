<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTrainingSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_training_id',
        'mentor_id',
        'schedule_time',
        'title',
        'activity_description',
        'activity_result',
        'mentor_notes',
        'mentor_assessment',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query
                ->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('activity_description', 'like', '%' . $keyword . '%')
                ->orWhere('activity_result', 'like', '%' . $keyword . '%')
                ->orWhere('mentor_notes', 'like', '%' . $keyword . '%')
                ->orWhere('mentor_assessment', 'like', '%' . $keyword . '%');
        });

        $query->when($filters['schedule_date'] ?? null, function ($query, $scheduleTime) {
            $query->whereDate('schedule_time', $scheduleTime);
        });

        $query->when($filters['mentor_id'] ?? null, function ($query, $mentorId) {
            if ($mentorId !== 'all') {
                $query->where('mentor_id', $mentorId);
            }
        });

        $query->when($filters['employee_id'] ?? null, function ($query, $mentorId) {
            if ($mentorId !== 'all') {
                $query->whereHas('employeeTraining', function ($q) use ($mentorId) {
                    $q->where('employee_id', $mentorId);
                });
            }
        });

        $query->when($filters['employee_training_id'] ?? null, function ($query, $employeeTrainingId) {
            if ($employeeTrainingId !== 'all') {
                $query->where('employee_training_id', $employeeTrainingId);
            }
        });
    }

    public function employeeTraining()
    {
        return $this->belongsTo(EmployeeTraining::class, 'employee_training_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Karyawan::class, 'mentor_id');
    }
}
