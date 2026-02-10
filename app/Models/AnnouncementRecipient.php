<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementRecipient extends Pivot
{
    protected $table = 'announcement_recipients';
    protected $hidden = ['updated_at', 'created_at'];

    protected $fillable = [
        'employee_id',
        'announcement_id',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function recipient()
    {
        return $this->belongsTo(Karyawan::class, 'employee_id');
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }
}
