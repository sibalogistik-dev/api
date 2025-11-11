<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Indonesia\Models\City;

class DetailDiri extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'employee_id'           => 'integer',
        'religion_id'           => 'integer',
        'place_of_birth_id'     => 'integer',
        'education_id'          => 'integer',
        'marriage_status_id'    => 'integer',
        'residential_area_id'   => 'integer',
    ];

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
        'marriage_status_id',
        'residential_area_id',
        'passport_photo',
        'id_card_photo',
        'drivers_license_photo',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }

    public function religion()
    {
        return $this->belongsTo(Agama::class, 'religion_id');
    }

    public function birthPlace()
    {
        return $this->belongsTo(City::class, 'place_of_birth_id', 'code');
    }

    public function education()
    {
        return $this->belongsTo(Pendidikan::class, 'education_id');
    }

    public function residentialArea()
    {
        return $this->belongsTo(City::class, 'residential_area_id', 'code');
    }

    public  function marriageStatus()
    {
        return $this->belongsTo(MarriageStatus::class)->withTrashed();
    }
}
