<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Village;

class Cabang extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'village_id' => 'integer',
        'company_id' => 'integer',
    ];

    protected $fillable = [
        'name',
        'address',
        'telephone',
        'village_id',
        'company_id',
        'start_time',
        'end_time',
        'latitude',
        'longitude',
        'attendance_radius',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('cabangs.name', 'like', "%{$keyword}%")
                    ->orWhereHas('company', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
            });
        });

        $query->when($filters['company_id'] ?? null, function ($query, $company) {
            if ($company !== 'all') {
                $query->whereHas('company', function ($query) use ($company) {
                    $query->where('id', $company);
                });
            }
        });
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'code');
    }

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function employee()
    {
        return $this->hasMany(Karyawan::class, 'branch_id');
    }
}
