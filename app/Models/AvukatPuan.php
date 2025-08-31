<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvukatPuan extends Model
{
    protected $table = 'avukat_puanlari';

    protected $fillable = [
        'is_id',
        'avukat_id',
        'puan',
        'yorum',
    ];

    public function islem()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }

    public function avukat()
    {
        return $this->belongsTo(Avukat::class, 'avukat_id');
    }
}
