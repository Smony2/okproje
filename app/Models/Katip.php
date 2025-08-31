<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Katip extends Authenticatable
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
        'phone',
        'tc_no',
        'avatar_url',
        'adres',
        'dogum_tarihi',
        'cinsiyet',
        'mezuniyet_universitesi',
        'mezuniyet_yili',
        'uzmanlik_alani',
        'puan',
        'giris_sayisi',
        'blokeli_mi',
        'notlar',
        'password',
        'is_active',
        'last_login_at',
        'username',
        'balance',
        'last_active_at',

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
        'blokeli_mi' => 'boolean',
        'last_login_at' => 'datetime',
        'dogum_tarihi' => 'date',
        'puan' => 'float',
        'mezuniyet_yili' => 'integer',
        'giris_sayisi' => 'integer',
        'last_active_at' => 'datetime',  // Veya 'timestamp' olarak da deneyebilirsin, ama 'datetime' öneririm
    ];

    /**
     * Configure the log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 'email', 'phone', 'is_active', 'last_login_at',
                'puan', 'giris_sayisi', 'blokeli_mi'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the katip's avatar URL.
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->attributes['avatar_url']) {
            return asset('uploads/' . $this->attributes['avatar_url']);
        }

        return asset('assets/images/default-avatar.png'); // boşsa default avatar göster
    }
    public function isler()
    {
        return $this->hasMany(Isler::class);
    }

    public function adliyeler()
    {
        return $this->belongsToMany(Adliye::class, 'katip_adliye');
    }

    public function puanlar()
    {
        return $this->hasMany(\App\Models\IsPuan::class, 'veren_id')
            ->where('veren_tipi', 'Avukat');
    }
    public function alinanPuanlar()
    {
        return $this->hasManyThrough(
            IsPuan::class,   // hedef
            Isler::class,    // ara tablo
            'katip_id',      // Isler.katip_id = Katip.id
            'is_id',         // IsPuan.is_id   = Isler.id
        )->where('veren_tipi', 'Avukat');
    }

    public function avatar()
    {
        return $this->hasOne(KatipAvatar::class);
    }

    public function teklifler()
    {
        return $this->hasMany(IsTeklifi::class, 'katip_id');
    }

    public function katipPuanlar()
    {
        return $this->hasMany(KatipPuan::class, 'katip_id');
    }

    // app/Models/Katip.php


    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable', 'user_type', 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

}
