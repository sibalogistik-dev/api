<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Indonesia\Models\City;

class Cabang extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'name',
        'address',
        'telephone',
        'city_id',
        'company_id',
        'start_time',
        'end_time',
        'latitude',
        'longitude'
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

        $query->when($filters['company'] ?? null, function ($query, $company) {
            if ($company !== 'all') {
                $query->whereHas('company', function ($query) use ($company) {
                    $query->where('id', $company);
                });
            }
        });
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'code')->orderBy('name');
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
