<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatipTransaction extends Model
{
    protected $table = 'katip_transactions';

    protected $fillable = [
        'katip_id',
        'is_id',
        'type',
        'amount',
        'status',
        'description',
    ];

    // İlişkiler
    public function katip()
    {
        return $this->belongsTo(Katip::class);
    }

    public function isleri()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }
}
