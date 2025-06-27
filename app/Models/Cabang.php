<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Kecamatan;
use Laravolt\Indonesia\Models\Province;

class Cabang extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'perusahaan_id',
        'kota_id',
        'latitude',
        'longitude',
    ];

    public function kota()
    {
        return $this->belongsTo(City::class, 'kota_id', 'code')->orderBy('name');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
