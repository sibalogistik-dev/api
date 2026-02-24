<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    protected $hidden = ['password', 'remember_token', 'updated_at', 'created_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'user_type',
        'email_verified_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('username', 'like', "%{$keyword}%");
        });
    }

    public function employee()
    {
        return $this->hasOne(Karyawan::class)->withTrashed();
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
}
