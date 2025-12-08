<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReprimandLetter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'letter_date',
        'reason',
        'issued_by',
        'notes',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'employee_id'   => 'integer',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->whereHas('employee', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('npk', 'like', "%{$keyword}%");
            });
        });

        $query->when($filters['employee_id'] ?? null, function ($query, $employeeId) {
            $query->where('employee_id', $employeeId);
        });
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
