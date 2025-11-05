<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'employee_id',
        'period_name',
        'period_start',
        'period_end',
        'salary_type',
        'base_salary',
        'days',
        'present_days',
        'half_days',
        'absent_days',
        'sick_days',
        'leave_days',
        'permission_days',
        'off_days',
        'overtime_minutes',
        'late_minutes',
        'deductions',
        'allowances',
        'overtime',
        'compensation',
        'net_salary',
        'generated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
