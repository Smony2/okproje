<?php

namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\Isler;
use App\Models\IsTeklifi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class KatipDasboardController extends Controller
{


// Controller güncellemesi - dashboard() metodunu değiştirin



    public function dashboard()
    {
        $katipId = auth('katip')->id();

        // Temel istatistikler
        $toplamIs = \App\Models\Isler::where('katip_id', $katipId)->count();
        $bekleyenIs = \App\Models\Isler::where('katip_id', $katipId)->where('durum', 'bekliyor')->count();
        $devamEdenIs = \App\Models\Isler::where('katip_id', $katipId)->where('durum', 'devam ediyor')->count();
        $tamamlananIs = \App\Models\Isler::where('katip_id', $katipId)->where('durum', 'tamamlandi')->count();
        $toplamKazanc = \App\Models\KatipTransaction::where('katip_id', $katipId)
            ->where('type', 'kazanc')
            ->sum('amount');

        // Yorum yapılan avukat sayısı
        $yorumYapilanAvukat = \App\Models\KatipPuan::where('katip_id', $katipId)
            ->with('islem')
            ->get()
            ->pluck('islem.avukat_id')
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
            ->where('katip_id', $katipId)
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
        $puanStats = \App\Models\KatipPuan::where('katip_id', $katipId)
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
            'bu_ay_is' => \App\Models\Isler::where('katip_id', $katipId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        // Diğer veriler...
        $islerim = Isler::where('katip_id', Auth::guard('katip')->id())->latest()->take(5)->get();
        $sonTeklifler = \App\Models\IsTeklifi::with(['isleri.adliye', 'katip'])
            ->whereHas('isleri', fn($q) => $q->where('katip_id', $katipId))
            ->latest()->take(5)->get();
        $sonKazanc = \App\Models\KatipTransaction::where('katip_id', $katipId)
            ->where('type', 'kazanc')->latest()->take(5)->get();

        // Bildirimler
        $katip = auth('katip')->user();
        $bildirimler = $katip->notifications()->latest()->take(8)->get();
        $okunmamisBildirimSayisi = $katip->unreadNotifications()->count();






        // Eski puan sistemi için backward compatibility
        $puanlar = $puanDagilimi['veriler'];

        return view('katip.dashboard', compact(
            'toplamIs', 'bekleyenIs', 'devamEdenIs', 'tamamlananIs', 'toplamKazanc',
            'yorumYapilanAvukat', 'islerim', 'aylikVeriler', 'puanDagilimi',
            'performansMetrikleri', 'sonTeklifler', 'sonKazanc',
            'bildirimler', 'okunmamisBildirimSayisi',
            'puanlar' // Eski sistem için
        ));
    }


}
