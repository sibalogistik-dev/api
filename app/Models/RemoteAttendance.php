<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RemoteAttendance extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->whereHas('employee', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('npk', 'like', "%{$keyword}%");
            });
        });

        $query->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
            $start_date = Carbon::parse($filters['start_date'])->startOfDay();
            $end_date   = Carbon::parse($filters['end_date'])->endOfDay();
            $query->whereBetween('start_date', [$start_date, $end_date]);
        });
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }
}
