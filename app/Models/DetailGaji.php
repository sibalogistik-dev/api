<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailGaji extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'employee_id',
        'salary_type',
        'monthly_base_salary',
        'daily_base_salary',
        'meal_allowance',
        'bonus',
        'allowance',
        'overtime',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
