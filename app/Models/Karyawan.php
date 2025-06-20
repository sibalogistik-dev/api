<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class Karyawan extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'agama_id',
        'no_telp',
        'tempat_lahir_id',
        'tanggal_lahir',
        'alamat',
        'golongan_darah',
        'pendidikan_id',
        'status_kawin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class);
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function tempat_lahir()
    {
        return $this->belongsTo(City::class, 'tempat_lahir_id', 'code');
    }

    public function detail_diri()
    {
        return $this->hasOne(DetailDiri::class);
    }

    public function detail_karyawan()
    {
        return $this->hasOne(DetailKaryawan::class);
    }

    public function detail_gaji()
    {
        return $this->hasOne(DetailGaji::class);
    }
}
