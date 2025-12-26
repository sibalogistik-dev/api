<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetMaintenance extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'asset_id',
        'creator_id',
        'maintenance_date',
        'min_maintenance_cost',
        'max_maintenance_cost',
        'actual_maintenance_cost',
        'description',
        'receipt',
        'approval_status'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('description', 'like', '%' . $keyword . '%')
                ->orWhereHas('creator', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('asset', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
        });
        $query->when($filters['creator_id'] ?? null, function ($query, $creatorId) {
            if ($creatorId !== 'all') {
                $query->where('creator_id', $creatorId);
            }
        });
        $query->when($filters['asset_id'] ?? null, function ($query, $assetId) {
            if ($assetId !== 'all') {
                $query->where('asset_id', $assetId);
            }
        });
        $query->when($filters['maintenance_date'] ?? null, function ($query, $maintenanceDate) {
            $query->whereDate('maintenance_date', $maintenanceDate);
        });
    }

    public function asset()
    {
        return $this->belongsTo(BranchAsset::class, 'asset_id');
    }

    public function creator()
    {
        return $this->belongsTo(Karyawan::class, 'creator_id');
    }
}
