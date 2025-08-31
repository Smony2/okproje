<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isler extends Model
{
    use HasFactory;

    protected $table = 'isler'; // tablo ismi (emin olmak için)

    protected $fillable = [
        'avukat_id',
        'katip_id',
        'adliye',
        'islem_tipi',
        'aciklama',
        'durum',
        'ucret',
        'avukat_onay',
        'katip_onay',
        'is_tamamlandi_at',
        'adliye_id',
        'aciliyet',
    ];

    protected $casts = [
        'avukat_onay' => 'boolean',
        'katip_onay' => 'boolean',
        'is_tamamlandi_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Avukat ilişki
    public function avukat()
    {
        return $this->belongsTo(Avukat::class, 'avukat_id');
    }

    // Katip ilişki
    public function katip()
    {
        return $this->belongsTo(Katip::class, 'katip_id');
    }

    // adliye ilişki

    public function adliye()
    {
        return $this->belongsTo(Adliye::class, 'adliye_id');
    }

    public function puanlar()
    {
        return $this->hasMany(IsPuan::class, 'is_id');
    }



    public function events()
    {
        return $this->hasMany(\App\Models\JobEvent::class, 'is_id')
            ->with('creator.avatar')    // creator polymorphic ilişkisi üzerinde avatar’ı da yükleyelim
            ->orderBy('created_at');
    }

    public function teklifler()
    {
        return $this->hasMany(IsTeklifi::class, 'is_id');
    }

    public function teslimatlar()
    {
        return $this->hasMany(IsTeslimat::class, 'is_id');
    }
    public function teklif()
    {
        return $this->hasOne(IsTeklifi::class, 'is_id')
            ->where('katip_id', auth('katip')->id());
    }

    public function teslimat()
    {
        return $this->hasOne(IsTeslimat::class, 'is_id');
    }


    //son eklendi yeni tablo açıldı avukat ve katip puanlar ayrı ayrı
    public function avukatPuanlar()
    {
        return $this->hasMany(AvukatPuan::class, 'is_id');
    }

    public function katipPuanlar()
    {
        return $this->hasMany(KatipPuan::class, 'is_id');
    }

    public function avukatPuan()
    {
        return $this->hasOne(AvukatPuan::class, 'is_id')->where('avukat_id', auth('avukat')->id());
    }
}
