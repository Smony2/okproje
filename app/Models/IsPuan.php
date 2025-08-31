<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsPuan extends Model
{
    protected $table = 'is_puanlari';

    protected $fillable = [
        'is_id',
        'veren_id',
        'veren_tipi',
        'puan',
        'yorum',
    ];

    // İş ile ilişkisi
    public function islem()
    {
        return $this->belongsTo(Isler::class, 'is_id');
    }
    // Puanı veren kişi (avukat veya katip)
    public function veren()
    {
        $morphMap = [
            'avukat' => Avukat::class,
            'katip'  => Katip::class,
        ];

        if (!isset($this->veren_tipi) || !array_key_exists($this->veren_tipi, $morphMap)) {
            \Log::warning('Geçersiz veren_tipi değeri: ', [
                'is_puan_id' => $this->id,
                'veren_tipi' => $this->veren_tipi,
                'veren_id' => $this->veren_id
            ]);
            return $this->belongsTo(Avukat::class, 'veren_id');
        }

        $type = $morphMap[$this->veren_tipi];
        return $this->belongsTo($type, 'veren_id');
    }

    public function alan()
    {
        $morphMap = [
            'avukat' => Avukat::class,
            'katip'  => Katip::class,
        ];

        $type = $morphMap[$this->attributes['alan_type'] ?? 'avukat'] ?? Avukat::class;

        return $this->belongsTo($type, 'veren_id');
    }



}
