<?php
// app/Models/AvukatTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvukatTransaction extends Model
{
    protected $table = 'avukat_transactions';

    protected $fillable = [
        'avukat_id',
        'type',
        'amount',
        'description',
        'status',
    ];

    // İlişki
    public function avukat()
    {
        return $this->belongsTo(Avukat::class);
    }
    public function user()
    {
        return $this->belongsTo(Avukat::class, 'avukat_id');
    }
}
