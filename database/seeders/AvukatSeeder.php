<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Avukat;
use Faker\Factory as Faker;

class AvukatSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        for ($i = 0; $i < 20; $i++) {
            Avukat::create([
                'name'                   => $faker->name(),
                'username'               => $faker->unique()->userName(),
                'email'                  => $faker->unique()->safeEmail(),
                'phone'                  => $faker->phoneNumber(),
                'tc_no'                  => $faker->unique()->numerify('###########'),
                'baro_no'                => $faker->unique()->numberBetween(10000, 99999),
                'baro_adi'               => $faker->randomElement(['İstanbul Barosu', 'Ankara Barosu', 'İzmir Barosu']),
                'password'               => Hash::make('12345678'),
                'avatar_url'             => null,
                'adres'                  => $faker->address(),
                'unvan'                  => $faker->randomElement(['Avukat', 'Başavukat']),

                // Burada DB’deki sütun isimleriyle eşleştirdik:
                'is_active'              => $faker->boolean(90),
                'dogum_tarihi'           => $faker->date(),
                'cinsiyet'               => $faker->randomElement(['Erkek', 'Kadın', 'Belirtmek istemiyor']),
                'mezuniyet_universitesi' => $faker->randomElement(['İstanbul Üniversitesi', 'Ankara Üniversitesi', 'Marmara Üniversitesi']),
                'mezuniyet_yili'         => $faker->numberBetween(2000, 2022),
                'uzmanlik_alani'         => $faker->randomElement(['Ceza Hukuku', 'Ticaret Hukuku', 'İş Hukuku', 'Aile Hukuku']),
                'puan'                   => $faker->randomFloat(2, 1, 5),
                'son_giris_at'           => $faker->dateTimeBetween('-30 days', 'now'),
                'giris_sayisi'           => $faker->numberBetween(1, 100),

                // Aynı şekilde blokeli alan adı:
                'blokeli_mi'             => $faker->boolean(10),

                'notlar'                 => $faker->optional()->sentence(),
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);
        }
    }
}
