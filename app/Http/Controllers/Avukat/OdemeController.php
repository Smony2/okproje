<?php
// app/Http/Controllers/Avukat/OdemeController.php

namespace App\Http\Controllers\Avukat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AvukatTransaction;

class OdemeController extends Controller
{
    // bakiye yükleme formu
    public function create()
    {
        $avukat = Auth::user();
        return view('avukat.odeme.yukle', compact('avukat'));
    }

    // bakiye yükleme işlemi
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $avukat = Auth::user();

        // Aynı kullanıcıya ait bekleyen (pending) bir ödeme varsa, yeni talep oluşturulamaz
        $existingPending = $avukat->transactions()
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return back()->with('error', 'Zaten bekleyen bir jeton yükleme talebiniz var. Lütfen onaylanmasını bekleyin.');
        }

        // Yeni yükleme talebi oluştur
        $avukat->transactions()->create([
            'type' => 'deposit',
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending', // ödeme onayı geldikten sonra 'completed' yapılır
        ]);

        return back()->with('success', 'Jeton yükleme talebiniz alındı, onay bekliyor.');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $avukat = auth('avukat')->user();

        // 1) İşlem kaydı
        $tx = $avukat->transactions()->create([
            'type'   => 'deposit',     // veya 'withdrawal'
            'amount' => $request->amount,
            'status' => 'completed',   // veya pending
        ]);

        // 2) Bakiye güncelle
        $avukat->balance = $avukat->balance + $tx->amount;
        $avukat->save();

        return back()->with('success', 'Bakiye başarıyla yüklendi.');
    }

    // finans geçmişi
    public function history()
    {
        $avukat = Auth::user();
        $transactions = $avukat->transactions()
            ->latest()
            ->paginate(20);

        return view('avukat.odeme.gecmis', compact('transactions'));
    }
}
