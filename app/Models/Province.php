<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }

    public function districts()
    {
        return $this->hasManyThrough(
            District::class,
            City::class,
            'province_code',
            'city_code',
            'code',
            'code'
        );
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
