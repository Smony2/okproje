<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatipAvatar extends Model
{
    protected $fillable = ['path', 'katip_id'];

    public function katip()
    {
        return $this->belongsTo(Katip::class);
    }
}
