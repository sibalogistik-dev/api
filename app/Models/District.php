<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $searchableColumns = ['code', 'name', 'city.name'];

    protected $casts = [
        'code'          => 'integer',
        'city_code' => 'integer',
    ];

    protected $table = 'districts';

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'district_code', 'code');
    }

    public function getCityNameAttribute()
    {
        return $this->city->name;
    }

    public function getProvinceNameAttribute()
    {
        return $this->city->province->name;
    }
}
