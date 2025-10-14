<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class DetailDiri extends Model
{
    protected $fillable = [
        'employee_id',
        'gender',
        'religion_id',
        'phone_number',
        'place_of_birth_id',
        'date_of_birth',
        'address',
        'blood_type',
        'education_id',
        'marriage_status',
        'residential_area_id',
        'passport_photo',
        'id_card_photo',
        'drivers_license_photo',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'religion_id');
    }

    public function tempat_lahir()
    {
        return $this->belongsTo(City::class, 'place_of_birth_id', 'code');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'education_id');
    }

    public function daerah_tinggal()
    {
        return $this->belongsTo(City::class, 'residential_area_id', 'code');
    }
}
