<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Katip;
use App\Models\Adliye;

class KatipAdliyeSeeder extends Seeder
{
    public function run(): void
    {
        // Tüm adliye id'lerini al
        $adliyeIds = Adliye::pluck('id')->toArray();

        // Her bir katip için
        Katip::all()->each(function (Katip $katip) use ($adliyeIds) {
            // 1 ile 3 arası rastgele kaç adliye atanacağı
            $count = rand(1, 3);

            // Id listesinden rastgele $count adet seç
            $selected = Arr::random($adliyeIds, $count);

            // Pivot tabloya yaz (önce varsa temizle)
            $katip->adliyeler()->sync($selected);
        });
    }
}
