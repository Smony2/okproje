<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BannedWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // JSON dosyasından küfür listesini oku
        $jsonFile = database_path('seeders/karaliste.json');
        
        if (file_exists($jsonFile)) {
            $bannedWords = json_decode(file_get_contents($jsonFile), true);
        } else {
            // Fallback: temel küfür listesi
            $bannedWords = [
                'amk', 'aq', 'mk', 'oç', 'sg', 'salak', 'aptal', 'gerizekalı', 'mal', 'ahmak',
                'orospu', 'piç', 'pezevenk', 'göt', 'sik', 'siktir', 'amcık', 'yarrak'
            ];
        }

        $data = [];
        $now = Carbon::now();

        foreach ($bannedWords as $word) {
            // Boş string kontrolü
            if (empty(trim($word))) {
                continue;
            }
            
            $data[] = [
                'word' => strtolower(trim($word)),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Veritabanını tamamen temizle ve yeni verileri ekle
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('banned_words')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Tek tek ekle (duplicate kontrolü ile)
        foreach ($data as $item) {
            try {
                DB::table('banned_words')->insert($item);
            } catch (\Exception $e) {
                // Duplicate entry'leri sessizce atla
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    throw $e;
                }
            }
        }
        
        $this->command->info('Yasaklı kelimeler eklendi: ' . count($data) . ' kelime');
    }
}
