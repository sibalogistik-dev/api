<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'user_id',
        'name',
        'npk',
        'job_title_id',
        'manager_id',
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

    public function jobTitle()
    {
        return $this->belongsTo(Jabatan::class, 'job_title_id');
    }

    public function branch()
    {
        return $this->belongsTo(Cabang::class, 'branch_id');
    }

    public function employeeDetails()
    {
        return $this->hasOne(DetailDiri::class, 'employee_id');
    }

    public function salaryDetails()
    {
        return $this->hasOne(DetailGaji::class, 'employee_id')->latest('id');
    }

    public function salaryHistory()
    {
        return $this->hasMany(DetailGaji::class, 'employee_id')->orderBy('id', 'desc');
    }

    public function manager()
    {
        return $this->belongsTo(Karyawan::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Karyawan::class, 'manager_id');
    }

    public function isManagerOf(Karyawan $targetKaryawan): bool
    {
        return $targetKaryawan->manager_id === $this->id;
    }

    public function isManagerOfRecursive(Karyawan $targetKaryawan): bool
    {
        $current = $targetKaryawan;

        while ($current->manager_id) {
            if ($current->manager_id === $this->id) {
                return true;
            }

            $current = $current->manager;
        }
        return false;
    }

    public function attendance()
    {
        return $this->hasMany(Absensi::class, 'employee_id');
    }
}
