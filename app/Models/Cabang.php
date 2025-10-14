<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Kecamatan;
use Laravolt\Indonesia\Models\Province;

class Cabang extends Model
{
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

    public function kota()
    {
        return $this->belongsTo(City::class, 'city_id', 'code')->orderBy('name');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'branch_id');
    }
}
