<?php

namespace App\Models;

use Carbon\Carbon;
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
        'issued_by'     => 'integer',
        'letter_date'   => 'date',
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

        $query->when($filters['issued_by'] ?? null, function ($query, $issuedBy) {
            $query->where('issued_by', $issuedBy);
        });

        $query->when($filters['letter_date'] ?? null, function ($query, $letterDate) {
            $query->whereDate('letter_date', $letterDate);
        });

        $query->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
            $start_date = Carbon::parse($filters['start_date'])->startOfDay();
            $end_date   = Carbon::parse($filters['end_date'])->endOfDay();
            $query->whereBetween('letter_date', [$start_date, $end_date]);
        });
    }

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id')->withTrashed();
    }

    public function issuer()
    {
        return $this->belongsTo(Karyawan::class, 'issued_by')->withTrashed();
    }
}
