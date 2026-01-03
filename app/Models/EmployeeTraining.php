<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTraining extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'training_type_id',
        'training_name',
        'start_date',
        'notes',
        'status',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query
                ->where('notes', 'like', '%' . $keyword . '%')
                ->orWhere('training_name', 'like', '%' . $keyword . '%')
                ->orWhereHas('employee', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
        });

        $query->when($filters['employee_id'] ?? null, function ($query, $karyawanId) {
            if ($karyawanId !== 'all') {
                $query->where('employee_id', $karyawanId);
            }
        });

        $query->when($filters['training_type_id'] ?? null, function ($query, $trainingTypeId) {
            if ($trainingTypeId !== 'all') {
                $query->where('training_type_id', $trainingTypeId);
            }
        });

        $query->when(array_key_exists('status', $filters) && $filters['status'] !== 'all', function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });
    }

    public function trainingType()
    {
        return $this->belongsTo(EmployeeTrainingType::class, 'training_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeTrainingSchedule::class, 'employee_training_id');
    }
}
