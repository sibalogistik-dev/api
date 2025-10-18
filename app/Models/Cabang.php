<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Kecamatan;
use Laravolt\Indonesia\Models\Province;

class Cabang extends Model
{
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
