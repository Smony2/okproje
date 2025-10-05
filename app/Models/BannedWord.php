<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    use HasFactory;

    protected $fillable = ['word', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Sadece aktif yasaklı kelimeleri getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

