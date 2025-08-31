<?php

namespace Database\Seeders;

use App\Models\Katip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class KatipSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('tr_TR');

        for ($i = 0; $i < 50; $i++) {
            Katip::create([
                'name' => $faker->name,
                'username'               => $faker->unique()->userName(),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'tc_no' => $faker->unique()->numerify('###########'),
                'password' => Hash::make('password123'), // Şifre sabit olacak
                'avatar_url' => null,
                'adres' => $faker->address,
                'adliyet' => $faker->randomElement([
                    'Çağlayan Adliyesi', 'Bakırköy Adliyesi', 'Kartal Adliyesi',
                    'Büyükçekmece Adliyesi', 'Anadolu Adliyesi', 'İstanbul Adliyesi'
                ]),
                'il' => 'İstanbul',
                'unvan' => $faker->randomElement(['Katip', 'Başkatip']),
                'aktif_mi' => $faker->boolean(90), // %90 aktif
                'dogum_tarihi' => $faker->date('Y-m-d', '2002-01-01'),
                'cinsiyet' => $faker->randomElement(['Erkek', 'Kadın', 'Belirtmek istemiyor']),
                'mezuniyet_okulu' => $faker->randomElement([
                    'İstanbul Üniversitesi', 'Marmara Üniversitesi', 'Anadolu Üniversitesi',
                    'Atatürk Üniversitesi', 'İstanbul Anadolu Lisesi'
                ]),
                'mezuniyet_yili' => $faker->numberBetween(2000, 2023),
                'uzmanlik_alani' => $faker->randomElement([
                    'Evrak Takibi', 'İcra Takibi', 'Mahkeme Takibi'
                ]),
                'puan' => $faker->randomFloat(2, 3, 5),
                'son_giris_at' => $faker->dateTimeThisMonth(),
                'giris_sayisi' => $faker->numberBetween(1, 500),
                'blokeli_mi' => $faker->boolean(10), // %10 blokeli
                'notlar' => $faker->sentence(),
                'toplam_yildiz' => $faker->randomFloat(2, 3, 5),
            ]);
        }
    }
}
