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






        // Bildirimler ve abonelik bilgileri
        $avukat = auth('avukat')->user();
        $subscription = $avukat->subscription;
        $remainingJobs = $avukat->remaining_jobs_in_period; // null => sınırsız
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
            'aylikIsler', 'puanlar', // Eski sistem için
            'subscription', 'remainingJobs'
        ));
    }
}
