<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatipPuan extends Model
{
    protected $table = 'katip_puanlari';

    protected $fillable = [
        'is_id',
        'katip_id',
        'puan',
        'yorum',
    ];

    public function islem()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }

    public function katip()
    {
        return $this->belongsTo(Katip::class, 'katip_id');
    }
}
