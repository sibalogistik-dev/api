<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTrainingType extends Model
{
    use SoftDeletes;

    protected $table = 'employee_training_types';

    protected $fillable = [
        'name'
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });
    }

    public function training()
    {
        return $this->hasMany(EmployeeTraining::class, 'training_type_id');
    }
}
