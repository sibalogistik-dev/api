<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $fillable = [
        'title',
        'content',
        'image_url',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%")
                    ->orWhereHas('recipients', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
            });
        });

        $query->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
            $start_date = Carbon::parse($filters['start_date'])->startOfDay();
            $end_date   = Carbon::parse($filters['end_date'])->endOfDay();
            $query->whereBetween('created_at', [$start_date, $end_date]);
        });

        $query->when($filters['employee_id'] ?? null, function ($query, $employeeId) {
            $query->whereHas('recipients', function ($q) use ($employeeId) {
                $q->where('karyawans.id', $employeeId);
            });
        });
    }

    public function recipients()
    {
        return $this->belongsToMany(Karyawan::class, 'announcement_recipients', 'announcement_id', 'employee_id')
            ->using(AnnouncementRecipient::class)
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
