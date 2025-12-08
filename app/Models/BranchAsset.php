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

        $query->when($filters['branch_id'] ?? null, function ($query, $branchId) {
            $query->where('branch_id', $branchId);
        });

        $query->when($filters['asset_type_id'] ?? null, function ($query, $assetTypeId) {
            $query->where('asset_type_id', $assetTypeId);
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
