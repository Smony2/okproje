<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{AvukatPuan, Isler, IsTeklifi, IsPuan, KatipPuan, KatipTransaction, IsTeslimat};
use Illuminate\Http\Request;

class AdminIslerController extends Controller
{
    public function index()
    {
        $isler = Isler::with(['avukat', 'katip', 'adliye'])
            ->latest()
            ->paginate(20);

        return view('admin.isler.index', compact('isler'));
    }

    public function show($id)
    {
        $is = Isler::with(['avukat', 'katip', 'adliye', 'teklifler.katip', 'avukatPuanlar','katipPuanlar'])
            ->findOrFail($id);

        return view('admin.isler.show', compact('is'));
    }

    public function avukatDegerlendirmeleri()
    {
        $puanlar = AvukatPuan::with(['islem.avukat'])
            ->latest()
            ->paginate(20);

        return view('admin.isler.avukatdegerlendirme', compact('puanlar'));
    }

    public function katipDegerlendirmeleri()
    {
        $puanlar = KatipPuan::with(['islem.katip'])
            ->latest()
            ->paginate(20);


        return view('admin.isler.katipdegerlendirme', compact('puanlar'));
    }

    public function teklifler()
    {
        $teklifler = IsTeklifi::with(['isleri', 'katip'])
            ->latest()
            ->paginate(20);

        return view('admin.isler.teklifler', compact('teklifler'));
    }

    public function teslimler()
    {
        $teslimler = IsTeslimat::with(['isleri.avukat', 'katip'])
            ->latest()
            ->paginate(20);

        return view('admin.isler.teslimler', compact('teslimler'));
    }

    public function katipKazanc()
    {
        $kazanc = KatipTransaction::with('katip')
            ->latest()
            ->paginate(20);

        return view('admin.isler.katipkazanc', compact('kazanc'));
    }
}
