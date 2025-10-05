<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'avukat_id',
        'subscription_id',
        'performed_by_admin_id',
        'change_type',
        'old_subscription_id', 'new_subscription_id',
        'old_duration_days', 'new_duration_days',
        'old_max_jobs', 'new_max_jobs',
        'old_start_date', 'old_end_date',
        'new_start_date', 'new_end_date',
        'extra_jobs_delta',
        'note',
    ];
}


