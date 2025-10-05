<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avukat;
use App\Models\AvukatPuan;
use App\Models\Isler;
use App\Models\IsPuan;
use App\Models\IsTeklifi;
use App\Models\Katip;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{

    public function dashboard()
    {
        // Son 5 log işlemi
        $logs = Activity::latest()->limit(5)->get();

        // Genel metrikler
        $toplamAvukat = Avukat::count();
        $toplamKatip = Katip::count();
        $aktifIsler = Isler::where('durum', 'devam ediyor')->count();
        $tamamlananIsler = Isler::where('durum', 'tamamlandi')->count();
        $iptalEdilenIsler = Isler::where('durum', 'iptal')->count(); // 'iptal edildi' yerine 'iptal' kullanıldı, eğer tablo yapısında durum 'iptal' ise
        $toplamKazanc = Isler::sum('ucret');


        // Son 10 avukat puanı
        $sonAvukatPuanlari = \App\Models\AvukatPuan::with(['avukat'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($puan) {
                $puan->veren_adi = $puan->avukat->username ?? '—';
                $puan->alan_adi = $puan->katip->username ?? '—';
                return $puan;
            });

        // Son 10 kâtip puanı
        $sonKatipPuanlari = \App\Models\KatipPuan::with([ 'katip'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($puan) {
                $puan->veren_adi = $puan->katip->username ?? '—';
                $puan->alan_adi = $puan->avukat->username ?? '—';
                return $puan;
            });

        $sonTeklifler = \App\Models\IsTeklifi::with(['isleri.adliye', 'katip', 'isleri.avukat'])
            ->latest()
            ->take(10)
            ->get();


        $sonYatirimlar = \App\Models\AvukatTransaction::with('avukat')
            ->where('type', 'deposit')
            ->latest()
            ->take(5)
            ->get();

        $sonIsler = \App\Models\Isler::with(['avukat', 'katip', 'adliye'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'logs',
            'toplamAvukat',
            'toplamKatip',
            'aktifIsler',
            'tamamlananIsler',
            'iptalEdilenIsler',
            'toplamKazanc',
            'sonAvukatPuanlari',
            'sonKatipPuanlari',
            'sonTeklifler',
            'sonYatirimlar',
            'sonIsler'
        ));
    }
}
