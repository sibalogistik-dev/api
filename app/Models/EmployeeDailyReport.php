<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDailyReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'job_description_id',
        'description',
    ];

    protected $casts = [
        'employee_id'           => 'integer',
        'job_description_id'    => 'integer',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->wherehas('employee', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })->orWhere('description', 'like', "%{$keyword}%");
        });
        $query->when($filters['employee_id'] ?? null, function ($query, $keyword) {
            $query->wherehas('employee', function ($query) use ($keyword) {
                $query->where('id', $keyword);
            });
        });
        $query->when($filters['start_date']     ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v));
        $query->when($filters['end_date']       ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v));
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }

    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class, 'job_description_id');
    }
}
