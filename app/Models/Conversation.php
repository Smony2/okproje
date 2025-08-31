<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'avukat_id',
        'katip_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function avukat()
    {
        return $this->belongsTo(Avukat::class);
    }

    public function katip()
    {
        return $this->belongsTo(Katip::class);
    }

    public function isleri()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }


}
