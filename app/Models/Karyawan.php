<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'npk',
        'job_title_id',
        'branch_id',
        'start_date',
        'end_date',
        'contract',
        'bank_account_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'job_title_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'branch_id');
    }

    public function detail_diri()
    {
        return $this->hasOne(DetailDiri::class, 'employee_id');
    }

    public function detail_gaji()
    {
        return $this->hasOne(DetailGaji::class, 'employee_id')->latest('id');
    }

    public function histori_gaji()
    {
        return $this->hasMany(DetailGaji::class, 'employee_id')->orderBy('id', 'desc');
    }
}
