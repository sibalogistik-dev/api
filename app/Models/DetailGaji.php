<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGaji extends Model
{
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'employee_id',
        'monthly_base_salary',
        'daily_base_salary',
        'meal_allowance',
        'bonus',
        'allowance',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
