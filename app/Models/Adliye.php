<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adliye extends Model
{
    use HasFactory;
    protected $table = 'adliyeler'; // tablo ismi (emin olmak için)

    protected $fillable = [
        'ad',
        'il',
        'adres',
        'aktif_mi',
        'aciklama',
        'telefon',
        'resimyol',
    ];

    protected $casts = [
        'aktif_mi' => 'boolean',
    ];

    // Örnek ilişki: bu adliyede görevli katipler


    // Örnek ilişki: bu adliyede oluşturulan işler
    public function isler()
    {
        return $this->hasMany(Isler::class);
    }



    public function katipler()
    {
        return $this->belongsToMany(Katip::class, 'katip_adliye');
    }
}
