<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'logoresimyol',
        'faviconyol',
        // Diğer ayar alanları buraya eklenebilir
    ];
} 