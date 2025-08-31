<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsTeklifi extends Model
{
    protected $table = 'is_teklifleri';

    protected $fillable = [
        'is_id',
        'katip_id',
        'jeton',
        'mesaj',
        'durum',
    ];

    public function isleri()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }

    public function katip()
    {
        return $this->belongsTo(Katip::class, 'katip_id');
    }
}
