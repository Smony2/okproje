<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class IsTeslimat extends Authenticatable
{
    protected $table = 'is_teslimatlar';

    protected $fillable = ['is_id', 'katip_id', 'aciklama', 'dosya_yolu'];

    public function isleri()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }

    public function katip()
    {
        return $this->belongsTo(Katip::class, 'katip_id');
    }

}
