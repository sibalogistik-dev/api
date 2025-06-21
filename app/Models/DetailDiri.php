<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class DetailDiri extends Model
{
    protected $fillable = [
        'karyawan_id',
        'jenis_kelamin',
        'agama_id',
        'no_telp',
        'tempat_lahir_id',
        'tanggal_lahir',
        'alamat',
        'golongan_darah',
        'pendidikan_id',
        'status_kawin',
        'daerah_tinggal_id',
        'pas_foto',
        'ktp_foto',
        'sim_foto',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function tempat_lahir()
    {
        return $this->belongsTo(City::class, 'tempat_lahir_id', 'code');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class);
    }

    public function daerah_tinggal()
    {
        return $this->belongsTo(City::class, 'daerah_tinggal_id', 'code');
    }
}
