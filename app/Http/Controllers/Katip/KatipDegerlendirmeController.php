<?php

namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\Avukat;
use App\Models\Katip;
use Illuminate\Support\Facades\Auth;
use App\Models\Isler;

class KatipDegerlendirmeController extends Controller
{
    public function index()
    {
        $katipId = Auth::guard('katip')->id();

        $isler = Isler::with(['avukat', 'avukatPuan'])
            ->where('katip_id', Auth::guard('katip')->id())
            ->whereNotNull('avukat_id')
            ->where('katip_onay', true)
            ->latest()
            ->get();

        return view('katip.degerlendirme.avukatlar', compact('isler'));
    }

    public function profil($id)
    {
        $avukat = Avukat::with([
            // tamamlanan işler + adliye + ilgili katip
            'isler' => fn ($q) => $q->where('durum', 'tamamlandi')
                ->with(['adliye', 'katip', 'puanlar' => fn ($q) =>
                $q->where('veren_tipi', 'Katip')->latest()
                ])
                ->latest()
        ])->findOrFail($id);

        // katiplerin verdiği tüm puanları topla
        $tumPuanlar = $avukat->isler->flatMap->puanlar;

        $ortalamaPuan = round($tumPuanlar->avg('puan'), 1);
        $yorumSayisi  = $tumPuanlar->count();

        return view('katip.degerlendirme.profil', compact(
            'avukat', 'ortalamaPuan', 'yorumSayisi', 'tumPuanlar'
        ));
    }
}
