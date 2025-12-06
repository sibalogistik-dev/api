<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReprimandLetter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'letter_date',
        'reason',
        'issued_by',
        'notes',
    ];

    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    protected $casts = [
        'employee_id'   => 'integer',
    ];
}
