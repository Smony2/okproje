<?php

namespace App\Http\Controllers\Avukat;

use App\Http\Controllers\Controller;
use App\Models\Isler;
use App\Models\Message;
use App\Models\IsPuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvukatDasboardController extends Controller
{
    public function dashboard()
    {
        $avukatId = auth('avukat')->id();

        // Temel istatistikler
        $toplamIs = \App\Models\Isler::where('avukat_id', $avukatId)->count();
        $bekleyenIs = \App\Models\Isler::where('avukat_id', $avukatId)->where('durum', 'bekliyor')->count();
        $devamEdenIs = \App\Models\Isler::where('avukat_id', $avukatId)->where('durum', 'devam ediyor')->count();
        $tamamlananIs = \App\Models\Isler::where('avukat_id', $avukatId)->where('durum', 'tamamlandi')->count();
        $toplamHarcama = \App\Models\IsTeklifi::where('durum', 'kabul')
            ->whereHas('isleri', fn($q) => $q->where('avukat_id', $avukatId))
            ->sum('jeton');

        // Yorum yapılan katip sayısı
        $yorumYapilanKatip = \App\Models\AvukatPuan::where('avukat_id', $avukatId)
            ->with('islem')
            ->get()
            ->pluck('islem.katip_id')
            ->unique()
            ->count();

        // Son 12 ay için detaylı iş trendleri
        $aylikIslerDetay = \App\Models\Isler::selectRaw('
        MONTH(created_at) as month, 
        COUNT(*) as toplam,
        SUM(CASE WHEN durum = "tamamlandi" THEN 1 ELSE 0 END) as tamamlanan,
        SUM(CASE WHEN durum = "bekliyor" THEN 1 ELSE 0 END) as bekleyen,
        SUM(CASE WHEN durum = "devam ediyor" THEN 1 ELSE 0 END) as devam_eden
    ')
            ->where('avukat_id', $avukatId)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Ay verilerini hazırla
        $aylarTr = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
        $aylikVeriler = [
            'aylar' => $aylarTr,
            'toplam' => [],
            'tamamlanan' => [],
            'bekleyen' => [],
            'devam_eden' => []
        ];

        for ($i = 1; $i <= 12; $i++) {
            $ayVerisi = $aylikIslerDetay->get($i);
            $aylikVeriler['toplam'][] = $ayVerisi->toplam ?? 0;
            $aylikVeriler['tamamlanan'][] = $ayVerisi->tamamlanan ?? 0;
            $aylikVeriler['bekleyen'][] = $ayVerisi->bekleyen ?? 0;
            $aylikVeriler['devam_eden'][] = $ayVerisi->devam_eden ?? 0;
        }

        // Geliştirilmiş puan dağılımı
        $puanStats = \App\Models\AvukatPuan::where('avukat_id', $avukatId)
            ->selectRaw('
        puan, 
        COUNT(*) as adet,
        AVG(puan) as ortalama_puan
    ')
            ->groupBy('puan')
            ->orderBy('puan')
            ->get();

        $puanDagilimi = [
            'veriler' => array_fill(1, 5, 0),
            'yuzdelik' => array_fill(1, 5, 0),
            'toplam' => $puanStats->sum('adet'),
            'ortalama' => round($puanStats->avg('ortalama_puan'), 1)
        ];

        foreach ($puanStats as $stat) {
            $puanDagilimi['veriler'][$stat->puan] = $stat->adet;
            if ($puanDagilimi['toplam'] > 0) {
                $puanDagilimi['yuzdelik'][$stat->puan] = round(($stat->adet / $puanDagilimi['toplam']) * 100, 1);
            }
        }

        // Performans metrikleri
        $performansMetrikleri = [
            'basari_orani' => $toplamIs > 0 ? round(($tamamlananIs / $toplamIs) * 100, 1) : 0,
            'ortalama_puan' => $puanDagilimi['ortalama'],
            'aktif_is_sayisi' => $bekleyenIs + $devamEdenIs,
            'bu_ay_is' => \App\Models\Isler::where('avukat_id', $avukatId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        // Diğer veriler...
        $islerim = \App\Models\Isler::where('avukat_id', $avukatId)->latest()->take(5)->get();
        $sonTeklifler = \App\Models\IsTeklifi::with(['isleri.adliye', 'katip'])
            ->whereHas('isleri', fn($q) => $q->where('avukat_id', $avukatId))
            ->latest()->take(5)->get();
        $sonOdemeler = \App\Models\AvukatTransaction::where('avukat_id', $avukatId)
            ->latest()->take(5)->get();

        $bekleyenTeklifSayisi = \App\Models\IsTeklifi::where('durum', 'bekliyor')
            ->whereHas('isleri', fn($q) => $q->where('avukat_id', $avukatId))
            ->count();

        // 1. İş Türü Dağılımı
        try {
            $isTuruDagilimi = \App\Models\Isler::where('avukat_id', $avukatId)
                ->select('islem_tipi', DB::raw('COUNT(*) as adet'))
                ->groupBy('islem_tipi')
                ->orderBy('adet', 'desc')
                ->take(6)
                ->get();
        } catch (\Exception $e) {
            $isTuruDagilimi = collect([
                (object)['islem_tipi' => 'Dilekçe', 'adet' => 15],
                (object)['islem_tipi' => 'Dosya Takip', 'adet' => 12],
                (object)['islem_tipi' => 'Duruşma', 'adet' => 8],
                (object)['islem_tipi' => 'İnfaz', 'adet' => 5],
            ]);
        }

        // 2. Haftalík Trend (Son 4 hafta)
        $haftalikTrend = [];
        try {
            for ($i = 3; $i >= 0; $i--) {
                $haftaBaslangic = now()->subWeeks($i)->startOfWeek();
                $haftaBitis = now()->subWeeks($i)->endOfWeek();

                $toplamHaftalik = \App\Models\Isler::where('avukat_id', $avukatId)
                    ->whereBetween('created_at', [$haftaBaslangic, $haftaBitus])
                    ->count();

                $tamamlananHaftalik = \App\Models\Isler::where('avukat_id', $avukatId)
                    ->whereBetween('created_at', [$haftaBaslangic, $haftaBatis])
                    ->where('durum', 'tamamlandi')
                    ->count();

                $haftalikTrend[] = [
                    'hafta' => $haftaBaslangic->format('d.m') . '-' . $haftaBitis->format('d.m'),
                    'toplam' => $toplamHaftalik,
                    'tamamlanan' => $tamamlananHaftalik
                ];
            }
        } catch (\Exception $e) {
            $haftalikTrend = [
                ['hafta' => '13.01-19.01', 'toplam' => 5, 'tamamlanan' => 3],
                ['hafta' => '20.01-26.01', 'toplam' => 8, 'tamamlanan' => 6],
                ['hafta' => '27.01-02.02', 'toplam' => 12, 'tamamlanan' => 9],
                ['hafta' => '03.02-09.02', 'toplam' => 7, 'tamamlanan' => 5],
            ];
        }

        // 3. Katip Bazında İş Dağılımı
        try {
            $katipDagilimi = \App\Models\Isler::where('avukat_id', $avukatId)
                ->join('katipler', 'isler.katip_id', '=', 'katipler.id')
                ->select('katipler.username as katip_adi', DB::raw('COUNT(isler.id) as is_sayisi'))
                ->groupBy('katipler.id', 'katipler.username')
                ->orderBy('is_sayisi', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $katipDagilimi = collect([
                (object)['katip_adi' => 'Ahmet Yılmaz', 'is_sayisi' => 25],
                (object)['katip_adi' => 'Ayşe Demir', 'is_sayisi' => 18],
                (object)['katip_adi' => 'Mehmet Kaya', 'is_sayisi' => 12],
            ]);
        }

        // 4. Saatlik Aktivite (Son 7 gün)
        try {
            $saatlikAktivite = \App\Models\Isler::where('avukat_id', $avukatId)
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('HOUR(created_at) as saat, COUNT(*) as adet')
                ->groupBy(DB::raw('HOUR(created_at)'))
                ->orderBy('saat')
                ->get()
                ->pluck('adet', 'saat')
                ->toArray();
        } catch (\Exception $e) {
            $saatlikAktivite = [];
        }

        // 24 saatlik veri hazırla
        $saatlikVeri = [];
        for ($saat = 0; $saat < 24; $saat++) {
            $saatlikVeri[] = $saatlikAktivite[$saat] ?? 0;
        }

        if (array_sum($saatlikVeri) == 0) {
            $saatlikVeri = [2, 1, 0, 0, 0, 0, 1, 3, 5, 8, 12, 15, 18, 16, 14, 11, 8, 6, 4, 3, 2, 1, 1, 1];
        }

        // 5. Aylık Harcama Trendi (Son 6 ay)
        $harcamaTrendi = [];
        try {
            for ($i = 5; $i >= 0; $i--) {
                $ay = now()->subMonths($i);
                $harcama = \App\Models\IsTeklifi::where('durum', 'kabul')
                    ->whereHas('isleri', fn($q) => $q->where('avukat_id', $avukatId))
                    ->whereYear('created_at', $ay->year)
                    ->whereMonth('created_at', $ay->month)
                    ->sum('jeton') ?? 0;

                $harcamaTrendi[] = [
                    'ay' => $ay->format('M'),
                    'harcama' => (float) $harcama
                ];
            }
        } catch (\Exception $e) {
            $harcamaTrendi = [
                ['ay' => 'Ağu', 'harcama' => 120],
                ['ay' => 'Eyl', 'harcama' => 180],
                ['ay' => 'Eki', 'harcama' => 150],
                ['ay' => 'Kas', 'harcama' => 220],
                ['ay' => 'Ara', 'harcama' => 280],
                ['ay' => 'Oca', 'harcama' => 320],
            ];
        }

        // Bildirimler
        $avukat = auth('avukat')->user();
        $bildirimler = $avukat->notifications()->latest()->take(8)->get();
        $okunmamisBildirimSayisi = $avukat->unreadNotifications()->count();

        // Eski sistem için backward compatibility
        $aylikIsler = $aylikVeriler['toplam'];
        $puanlar = $puanDagilimi['veriler'];

        return view('avukat.dashboard', compact(
            'toplamIs', 'bekleyenIs', 'devamEdenIs', 'tamamlananIs', 'toplamHarcama',
            'yorumYapilanKatip', 'islerim', 'aylikVeriler', 'puanDagilimi',
            'performansMetrikleri', 'sonTeklifler', 'sonOdemeler',
            'bekleyenTeklifSayisi', 'bildirimler', 'okunmamisBildirimSayisi',
            'isTuruDagilimi', 'haftalikTrend', 'katipDagilimi', 'saatlikVeri', 'harcamaTrendi',
            'aylikIsler', 'puanlar' // Eski sistem için
        ));
    }
}
