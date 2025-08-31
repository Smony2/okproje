<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'is_id',
        'type',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Bildirimin sahibi (Avukat veya Katip)
     */
    public function notifiable()
    {
        return $this->morphTo('notifiable', 'user_type', 'user_id');
    }

    /**
     * İlgili iş
     */
    public function isleri()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }
}
