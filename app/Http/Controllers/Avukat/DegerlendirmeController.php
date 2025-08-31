<?php

namespace App\Http\Controllers\Avukat;

use App\Http\Controllers\Controller;
use App\Models\AvukatPuan;
use App\Models\Katip;
use App\Models\KatipPuan;
use Illuminate\Support\Facades\Auth;
use App\Models\Isler;

class DegerlendirmeController extends Controller
{
    public function index()
    {
        $avukatId = Auth::guard('avukat')->id();

        $isler = Isler::with(['katip'])
            ->where('avukat_id', $avukatId)
            ->where('avukat_onay', true)
            ->whereNotNull('katip_id')
            ->latest()
            ->get();

        return view('avukat.degerlendirme.katipler', compact('isler'));
    }

    public function profil($id)
    {
        // Kâtibi ve ilişkili verileri al
        $katip = Katip::with([
            'adliyeler',
            // tamamlanan işler + adliye + ilgili avukat
            'isler' => fn ($q) => $q->where('durum', 'tamamlandi')
                ->with('adliye', 'avukat')
                ->latest(),
        ])->findOrFail($id);

        // Avukatların bu kâtibe verdiği puanlar
        $avukatPuanlar = AvukatPuan::whereHas('islem', fn($q) => $q->where('katip_id', $katip->id))
            ->with(['islem', 'avukat'])
            ->latest()
            ->get();

        $avukatOrtalamaPuan = $avukatPuanlar->isNotEmpty() ? round($avukatPuanlar->avg('puan'), 1) : 0;
        $avukatYorumSayisi = $avukatPuanlar->count();

        // Kâtiplerin bu kâtibe verdiği puanlar (eğer başka kâtipler de puan verebiliyorsa)
        $katipPuanlar = KatipPuan::whereHas('islem', fn($q) => $q->where('katip_id', $katip->id))
            ->with(['islem', 'katip'])
            ->latest()
            ->get();

        $katipOrtalamaPuan = $katipPuanlar->isNotEmpty() ? round($katipPuanlar->avg('puan'), 1) : 0;
        $katipYorumSayisi = $katipPuanlar->count();

        return view('avukat.degerlendirme.profil',
            compact(
                'katip',
                'avukatOrtalamaPuan',
                'avukatYorumSayisi',
                'avukatPuanlar',
                'katipOrtalamaPuan',
                'katipYorumSayisi',
                'katipPuanlar'
            ));
    }

    public function favori()
    {
        $avukat = auth('avukat')->user();

        // Avukatın tamamlanmış işlerini al ve kâtipleri yükle
        $isler = Isler::where('avukat_id', $avukat->id)
            ->where('durum', 'tamamlandi')
            ->with(['katip' => function ($query) {
                $query->select('id', 'username'); // Sadece gerekli alanları al
            }])
            ->get();

        // Kâtipleri grupla, iş sayısına göre sırala ve ilk 5'ini al
        $katipler = $isler->groupBy('katip_id')
            ->map(function ($isGrubu) {
                $katip = $isGrubu->first()->katip;
                if (!$katip) return null;
                $katip->is_sayisi = $isGrubu->count(); // İş sayısını ekle
                $katip->son_is_tarihi = $isGrubu->max('updated_at'); // Son iş tarihi
                return $katip;
            })
            ->filter() // Null kâtipleri çıkar
            ->sortByDesc('is_sayisi') // İş sayısına göre sırala
            ->take(5) // İlk 5 kâtibi al
            ->values();

        return view('avukat.degerlendirme.favori', compact('katipler'));
    }
}
