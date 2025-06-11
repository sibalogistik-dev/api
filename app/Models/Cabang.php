<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Kecamatan;

class Cabang extends Model {
    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'perusahaan_id',
        'kota_id',
        'latitude',
        'longitude',
    ];

    public function kota() {
        return $this->belongsTo(City::class, 'kota_id', 'code');
    }

    public function perusahaan() {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }
}
