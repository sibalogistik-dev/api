<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'indonesia_villages';

    protected $casts = [
        'code'          => 'integer',
        'district_code' => 'integer',
    ];

    protected $searchableColumns = ['code', 'name', 'district.name'];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function getDistrictNameAttribute()
    {
        return $this->district->name;
    }

    public function getCityNameAttribute()
    {
        return $this->district->city->name;
    }

    public function getProvinceNameAttribute()
    {
        return $this->district->city->province->name;
    }
}
