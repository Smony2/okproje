<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'monthly_price',
        'duration_days',
        'max_jobs',
        'priority_support',
        'advanced_analytics',
        'custom_branding',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_jobs' => 'integer',
        'priority_support' => 'boolean',
        'advanced_analytics' => 'boolean',
        'custom_branding' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the avukats that have this subscription.
     */
    public function avukats()
    {
        return $this->hasMany(Avukat::class);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        $value = $this->price ?? $this->monthly_price ?? 0;
        return '₺' . number_format($value, 2);
    }

    /**
     * Check if subscription has unlimited jobs.
     */
    public function hasUnlimitedJobs()
    {
        return is_null($this->max_jobs);
    }

}
