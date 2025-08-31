<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobEvent extends Model
{
    use HasFactory;

    protected $table = 'job_events';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'is_id',
        'event_type',
        'description',
        'metadata',
        'creator_type',
        'creator_id',
    ];

    /**
     * Cast JSON metadata to array automatically.
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * İlişki: Bu event hangi işe (Isler) ait?
     */
    public function islem()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }

    /**
     * Polimorfik ilişki: Event'i oluşturan (Avukat veya Katip).
     */
    public function creator()
    {
        return $this->morphTo();
    }
}
