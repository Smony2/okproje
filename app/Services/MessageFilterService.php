<?php

namespace App\Services;

use App\Models\BannedWord;
use Illuminate\Support\Facades\Cache;

class MessageFilterService
{
    /**
     * Mesajda küfür var mı kontrol et
     * 
     * @param string $text
     * @return array ['contains' => bool, 'matched_word' => string|null]
     */
    public function containsProfanity($text)
    {
        $bannedWords = $this->getBannedWords();
        
        if (empty($bannedWords)) {
            return ['contains' => false, 'matched_word' => null];
        }
        
        // Mesajı normalize et (Türkçe karakterleri değiştir)
        $normalizedText = $this->normalizeText($text);
        
        foreach ($bannedWords as $word) {
            $normalizedWord = $this->normalizeText($word);
            
            // Kelime sınırları ile kontrol et (\b regex word boundary)
            // Büyük/küçük harf duyarsız
            if (preg_match('/\b' . preg_quote($normalizedWord, '/') . '\b/iu', $normalizedText)) {
                return ['contains' => true, 'matched_word' => $word];
            }
        }
        
        return ['contains' => false, 'matched_word' => null];
    }
    
    /**
     * Mesajda telefon numarası var mı kontrol et
     * 
     * @param string $text
     * @return array ['contains' => bool, 'matched_number' => string|null]
     */
    public function containsPhoneNumber($text)
    {
        // Türkiye telefon numarası regex patterns
        $patterns = [
            '/0(5\d{2})[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',           // 0532 123 45 67
            '/\+?90[ -]?5\d{2}[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',   // +90 532 123 45 67
            '/\(0?5\d{2}\)[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',       // (0532) 123 45 67
            '/05\d{9}/',                                            // 05321234567
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return ['contains' => true, 'matched_number' => $matches[0]];
            }
        }
        
        return ['contains' => false, 'matched_number' => null];
    }
    
    /**
     * Veritabanından aktif yasaklı kelimeleri getir (cache ile)
     * 
     * @return array
     */
    private function getBannedWords()
    {
        return Cache::remember('banned_words', 600, function () {
            return BannedWord::active()->pluck('word')->toArray();
        });
    }
    
    /**
     * Türkçe karakterleri normalize et (ı->i, ü->u, vb.)
     * 
     * @param string $text
     * @return string
     */
    private function normalizeText($text)
    {
        $replacements = [
            'ı' => 'i', 'İ' => 'I',
            'ğ' => 'g', 'Ğ' => 'G',
            'ü' => 'u', 'Ü' => 'U',
            'ş' => 's', 'Ş' => 'S',
            'ö' => 'o', 'Ö' => 'O',
            'ç' => 'c', 'Ç' => 'C',
        ];
        
        return strtr($text, $replacements);
    }
    
    /**
     * Cache'i temizle (yeni kelime eklendiğinde veya değişiklik olduğunda)
     */
    public static function clearCache()
    {
        Cache::forget('banned_words');
    }
}

