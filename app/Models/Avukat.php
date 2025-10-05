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
        'subscription_id',
        'subscription_start_date',
        'subscription_end_date',
        'subscription_is_active',



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
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
        'subscription_is_active' => 'boolean',
        'extra_job_credits' => 'integer',


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

    /**
     * Get the subscription that belongs to the avukat.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if avukat has an active subscription.
     */
    public function hasActiveSubscription()
    {
        return $this->subscription_is_active && 
               $this->subscription_end_date && 
               $this->subscription_end_date->isFuture();
    }

    /**
     * Get the subscription status.
     */
    public function getSubscriptionStatusAttribute()
    {
        if (!$this->subscription_is_active) {
            return 'inactive';
        }

        if (!$this->subscription_end_date) {
            return 'unknown';
        }

        if ($this->subscription_end_date->isFuture()) {
            return 'active';
        }

        return 'expired';
    }

    /**
     * Check if avukat can create more jobs based on subscription limits.
     */
    public function canCreateJob()
    {
        if (!$this->subscription || !$this->hasActiveSubscription()) {
            return false;
        }

        if ($this->subscription->hasUnlimitedJobs()) {
            return true;
        }

        $periodStart = $this->subscription_start_date?->startOfDay();
        $periodEnd   = $this->subscription_end_date?->endOfDay();

        $currentJobCount = $this->isler()
            ->when($periodStart, fn($q) => $q->where('created_at', '>=', $periodStart))
            ->when($periodEnd, fn($q) => $q->where('created_at', '<=', $periodEnd))
            ->whereNotIn('durum', ['reddedildi', 'iptal'])
            ->count();

        $baseLimit = (int)($this->subscription->max_jobs ?? 0);
        $extra = (int)($this->extra_job_credits ?? 0);
        return $currentJobCount < ($baseLimit + $extra);
    }

    /**
     * Check if avukat can work with more katips based on subscription limits.
     */
    public function canWorkWithKatip()
    {
        if (!$this->subscription) {
            return false;
        }

        if ($this->subscription->hasUnlimitedKatips()) {
            return true;
        }

        // Bu kısım katip ilişkilerine göre güncellenebilir
        return true; // Şimdilik true döndürüyoruz
    }

    public function getUsedJobsInPeriodAttribute()
    {
        if (!$this->subscription || !$this->hasActiveSubscription()) {
            return 0;
        }

        $periodStart = $this->subscription_start_date?->startOfDay();
        $periodEnd   = $this->subscription_end_date?->endOfDay();

        return $this->isler()
            ->when($periodStart, fn($q) => $q->where('created_at', '>=', $periodStart))
            ->when($periodEnd, fn($q) => $q->where('created_at', '<=', $periodEnd))
            ->whereNotIn('durum', ['reddedildi', 'iptal'])
            ->count();
    }

    public function getRemainingJobsInPeriodAttribute()
    {
        if (!$this->subscription || !$this->hasActiveSubscription()) {
            return 0;
        }

        if ($this->subscription->hasUnlimitedJobs()) {
            return null; // sınırsız için null
        }

        $max = (int)($this->subscription->max_jobs ?? 0);
        $used = (int)$this->used_jobs_in_period;
        $remaining = max(0, $max - $used);
        return $remaining;
    }
}
