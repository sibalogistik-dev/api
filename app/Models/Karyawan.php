<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'user_id'       => 'integer',
        'job_title_id'  => 'integer',
        'manager_id'    => 'integer',
        'branch_id'     => 'integer',
        'is_manager'    => 'boolean',
    ];

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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query
                ->where('karyawans.name', 'like', "%{$keyword}%")
                ->orWhere('karyawans.npk', 'like', "%{$keyword}%")
                ->orWhereHas('branch', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('branch.company', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('codename', 'like', "%{$keyword}%");
                });
        });
        $query->when($filters['branch'] ?? null, function ($query, $branch) {
            if ($branch !== 'all') {
                $query->where('branch_id', $branch);
            }
        });
    }

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

    public function isManagerOf(Karyawan $targetKaryawan)
    {
        return $targetKaryawan->manager_id === $this->id;
    }

    public function isManagerOfRecursive(Karyawan $targetKaryawan)
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

    public function remoteAttendances()
    {
        return $this->hasMany(RemoteAttendance::class, 'employee_id');
    }

    public function overtime()
    {
        return $this->hasMany(Overtime::class, 'employee_id');
    }

    public function faceRecognitionModel()
    {
        return $this->hasMany(FaceRecognitionModel::class, 'employee_id');
    }
}
