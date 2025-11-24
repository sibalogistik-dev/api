<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDailyReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'job_description_id',
        'description',
    ];

    protected $casts = [
        'employee_id'           => 'integer',
        'job_description_id'    => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }

    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class, 'job_description_id');
    }
}
