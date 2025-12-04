<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchAsset extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'branch_id',
        'asset_type_id',
        'is_vehicle',
        'name',
        'price',
        'purchase_date',
        'description',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where(function ($query) use ($keyword) {
                $query
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhereHas('branch', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('assetType', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
            });
        });
    }

    public function branch()
    {
        return $this->belongsTo(Cabang::class, 'branch_id');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
}
