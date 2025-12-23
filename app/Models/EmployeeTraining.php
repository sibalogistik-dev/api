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
        'start_date',
        'notes',
        'status',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->whereHas('karyawan', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        });

        $query->when($filters['karyawan_id'] ?? null, function ($query, $karyawanId) {
            if ($karyawanId !== 'all') {
                $query->where('karyawan_id', $karyawanId);
            }
        });

        $query->when($filters['training_type_id'] ?? null, function ($query, $trainingTypeId) {
            if ($trainingTypeId !== 'all') {
                $query->where('training_type_id', $trainingTypeId);
            }
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
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
}
