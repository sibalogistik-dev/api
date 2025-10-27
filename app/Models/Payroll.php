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
        'monthly_base_salary',
        'total_work_days',
        'total_absent_days',
        'total_late_minutes',
        'overtime_hours',
        'deductions',
        'allowances',
        'net_salary',
        'generated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
