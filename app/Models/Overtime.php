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

        $query->when($filters['start_date']     ?? null, fn($q, $v) => $q->whereDate('start_time', '>=', $v));
        $query->when($filters['end_date']       ?? null, fn($q, $v) => $q->whereDate('end_time', '<=', $v));
        $query->when($filters['employee_id']    ?? null, fn($q, $v) => $q->where('employee_id', $v));

        if (array_key_exists('approved', $filters)) {
            $approved   = $filters['approved'];
            if ($approved == 0 || $approved == 1) {
                $query->where('approved', $approved);
            }
        }
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }
}
