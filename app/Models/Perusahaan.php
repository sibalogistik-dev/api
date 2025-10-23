<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'codename',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('codename', 'like', "%{$keyword}%");
        });
    }

    public function branches()
    {
        return $this->hasMany(Cabang::class, 'company_id');
    }
}
