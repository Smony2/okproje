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
        // Türkiye telefon numarası regex patterns - daha kapsamlı
        $patterns = [
            // 0534 867 96 72 formatları
            '/0(5\d{2})[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',           // 0532 123 45 67, 0532-123-45-67
            '/\+?90[ -]?5\d{2}[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',   // +90 532 123 45 67, +90-532-123-45-67
            '/\(0?5\d{2}\)[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',       // (0532) 123 45 67, (532) 123 45 67
            
            // 534 867 96 72 formatları (0 olmadan)
            '/5\d{2}[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',              // 532 123 45 67, 532-123-45-67
            '/\(5\d{2}\)[ -]?\d{3}[ -]?\d{2}[ -]?\d{2}/',          // (532) 123 45 67
            
            // Ardışık 10-11 haneli sayılar
            '/05\d{9}/',                                            // 05321234567
            '/5\d{9}/',                                             // 5321234567
            '/05\d{8}/',                                            // 0532123456 (9 haneli)
            
            // Daha esnek pattern - 10-11 haneli telefon numarası benzeri sayılar
            '/(?<!\d)([05]\d{9,10})(?!\d)/',                       // 10-11 haneli sayılar
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $matchedNumber = $matches[0];
                
                // Eğer sadece sayı ise, telefon numarası formatında olup olmadığını kontrol et
                if (preg_match('/^\d+$/', $matchedNumber)) {
                    $digits = preg_replace('/\D/', '', $matchedNumber);
                    
                    // 10-11 haneli ve 5 ile başlayan sayıları telefon numarası olarak kabul et
                    if ((strlen($digits) == 10 && substr($digits, 0, 1) == '5') ||
                        (strlen($digits) == 11 && substr($digits, 0, 2) == '05')) {
                        return ['contains' => true, 'matched_number' => $matchedNumber];
                    }
                } else {
                    // Formatlı numaralar için direkt döndür
                    return ['contains' => true, 'matched_number' => $matchedNumber];
                }
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

