<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_active',
        'last_login_at',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Configure the log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active', 'last_login_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the roles that belong to the admin.
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role', 'admin_id', 'admin_role_id');
    }

    public function hasRole($slug)
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    /**
     * Check if the admin has a specific role.
     *
     * @param string $role
     * @return bool
     */

    /**
     * Get the admin's avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('uploads/' . $this->avatar);
        }

        return null;
    }
}
