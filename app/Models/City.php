<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $casts = [
        'code'          => 'integer',
        'province_code' => 'integer',
    ];

    protected $searchableColumns = ['code', 'name', 'province.name'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_code', 'code');
    }

    public function villages()
    {
        return $this->hasManyThrough(
            Village::class,
            District::class,
            'city_code',
            'district_code',
            'code',
            'code'
        );
    }

    public function getProvinceNameAttribute()
    {
        return $this->province->name;
    }

    public function getLogoPathAttribute()
    {
        $folder = 'indonesia-logo/';
        $id = $this->getAttributeValue('id');
        $arr_glob = glob(public_path() . '/' . $folder . $id . '.*');

        if (count($arr_glob) == 1) {
            $logo_name = basename($arr_glob[0]);

            return url($folder . $logo_name);
        }
    }
}
