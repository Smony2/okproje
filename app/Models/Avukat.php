<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Avukat extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'tc_no',
        'baro_no',
        'baro_adi',
        'password',
        'avatar_url',
        'adres',
        'unvan',
        'aktif_mi',
        'dogum_tarihi',
        'cinsiyet',
        'mezuniyet_universitesi',
        'mezuniyet_yili',
        'uzmanlik_alani',
        'puan',
        'son_giris_at',
        'giris_sayisi',
        'blokeli_mi',
        'notlar',
        'username',
        'balance',
        'last_active_at',
        'is_active',



    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'aktif_mi' => 'boolean',
        'blokeli_mi' => 'boolean',
        'son_giris_at' => 'datetime',
        'dogum_tarihi' => 'date',
        'mezuniyet_yili' => 'integer',
        'giris_sayisi' => 'integer',
        'puan' => 'float',
        'balance' => 'decimal:2',
        'last_active_at' => 'datetime',  // Veya 'timestamp' olarak da deneyebilirsin, ama 'datetime' öneririm


    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["name", "email", "aktif_mi", "son_giris_at"])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function roles()
    {
        return $this->belongsToMany(AvukatRole::class, 'finance_role', 'finance_id', 'finance_role_id');
    }

    public function hasRole($slug)
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function isler()
    {
        return $this->hasMany(Isler::class);
    }

    public function transactions()
    {
        return $this->hasMany(AvukatTransaction::class, 'avukat_id');
    }

    public function avatarial()
    {
        return $this->hasOne(Avatar::class);
    }


    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    public function avukatPuanlar()
    {
        return $this->hasMany(KatipPuan::class, 'avukat_id');
    }

    // app/Models/Avukat.php
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable', 'user_type', 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function unreadMessages()
    {
        return $this->hasManyThrough(
            Message::class,
            Conversation::class,
            'avukat_id', // Conversation’daki foreign key
            'conversation_id', // Message’daki foreign key
            'id', // Avukat’taki local key
            'id' // Conversation’daki local key
        )->where('receiver_type', 'Avukat')
            ->whereNull('read_at');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'avukat_id');
    }
}
