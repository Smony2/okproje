<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'avukat_id',
    ];

    public function avukat()
    {
        return $this->belongsTo(Avukat::class);
    }
}
