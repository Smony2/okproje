<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Avukat;
use App\Models\Katip;
use App\Models\Isler; // doğru model

use Illuminate\Support\Str;

class IslerSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('tr_TR');

        $avukatlar = Avukat::pluck('id')->toArray();
        $katipler = Katip::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            Isler::create([
                'avukat_id' => $faker->randomElement($avukatlar),
                'katip_id' => $faker->optional()->randomElement($katipler),
                'adliye' => $faker->randomElement([
                    'Çağlayan Adliyesi', 'Bakırköy Adliyesi', 'Kartal Adliyesi',
                    'Anadolu Adliyesi', 'Büyükçekmece Adliyesi', 'Gaziosmanpaşa Adliyesi'
                ]),
                'islem_tipi' => $faker->randomElement([
                    'Evrak Takibi', 'Duruşma Listesi Alımı', 'Dosya Kontrolü',
                    'İcra Takibi', 'Mahkeme Evrak Teslimi'
                ]),
                'aciklama' => $faker->sentence(),
                'durum' => $faker->randomElement(['bekliyor', 'devam ediyor', 'tamamlandi', 'iptal']),
                'ucret' => $faker->randomFloat(2, 100, 1000),
                'avukat_onay' => $faker->boolean(70),
                'katip_onay' => $faker->boolean(80),
                'is_tamamlandi_at' => $faker->optional()->dateTimeBetween('-30 days', 'now'),
                'created_at' => $faker->dateTimeBetween('-60 days', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
