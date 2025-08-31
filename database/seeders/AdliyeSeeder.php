<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Adliye;

class AdliyeSeeder extends Seeder
{
    public function run(): void
    {
        $adliyeler = [
            [
                'ad' => 'İstanbul Çağlayan Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Şişli',
                'adres' => 'Harbiye Mah. Adalet Sarayı Cad. No:1',
                'telefon' => '0212 123 45 67',
                'konum_linki' => 'https://maps.google.com/?q=Istanbul+Caglayan+Adliyesi',
                'aktif_mi' => true,
            ],
            [
                'ad' => 'Bakırköy Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Bakırköy',
                'adres' => 'İncirli Cad. No:7',
                'telefon' => '0212 234 56 78',
                'konum_linki' => 'https://maps.google.com/?q=Bakirkoy+Adliyesi',
                'aktif_mi' => true,
            ],
            [
                'ad' => 'Kartal Anadolu Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Kartal',
                'adres' => 'Orhantepe Mah. Adliye Cad. No:12',
                'telefon' => '0216 345 67 89',
                'konum_linki' => 'https://maps.google.com/?q=Kartal+Anadolu+Adliyesi',
                'aktif_mi' => true,
            ],
            [
                'ad' => 'Büyükçekmece Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Büyükçekmece',
                'adres' => 'Fatih Mah. Mahkeme Cad. No:5',
                'telefon' => '0212 456 78 90',
                'konum_linki' => 'https://maps.google.com/?q=Buyukcekmece+Adliyesi',
                'aktif_mi' => true,
            ],
            [
                'ad' => 'Gaziosmanpaşa Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Gaziosmanpaşa',
                'adres' => 'Karadeniz Mah. Adliye Sk. No:10',
                'telefon' => '0212 567 89 01',
                'konum_linki' => 'https://maps.google.com/?q=Gaziosmanpasa+Adliyesi',
                'aktif_mi' => true,
            ],
            [
                'ad' => 'Küçükçekmece Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Küçükçekmece',
                'adres' => 'Atatürk Mah. Adalet Cad. No:3',
                'telefon' => '0212 678 90 12',
                'konum_linki' => 'https://maps.google.com/?q=Kucukcekmece+Adliyesi',
                'aktif_mi' => true,
            ],
            [
                'ad' => 'Sultanahmet Adliyesi',
                'il' => 'İstanbul',
                'ilce' => 'Fatih',
                'adres' => 'Sultanahmet Meydanı No:1',
                'telefon' => '0212 789 01 23',
                'konum_linki' => 'https://maps.google.com/?q=Sultanahmet+Adliyesi',
                'aktif_mi' => true,
            ],
        ];

        foreach ($adliyeler as $adliye) {
            Adliye::create($adliye);
        }
    }
}
