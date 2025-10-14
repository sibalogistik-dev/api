<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGaji extends Model
{
    protected $fillable = [
        'employee_id',
        'monthly_base_salary',
        'daily_base_salary',
        'meal_allowance',
        'bonus',
        'allowance',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
