<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RemoteAttendance extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }
}
