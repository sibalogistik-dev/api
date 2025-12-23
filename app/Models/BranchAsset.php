<?php

namespace App\Models;

use Carbon\Carbon;
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
        'quantity',
        'image_path',
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

        $query->when(array_key_exists('is_vehicle', $filters) && $filters['is_vehicle'] !== 'all', function ($query) use ($filters) {
            $query->where('is_vehicle', $filters['is_vehicle']);
        });

        $query->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
            $start_date = Carbon::parse($filters['start_date'])->startOfDay();
            $end_date   = Carbon::parse($filters['end_date'])->endOfDay();
            $query->whereBetween('purchase_date', [$start_date, $end_date]);
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
