<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvukatTransaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $odemeler = AvukatTransaction::with('avukat')
            ->latest()
            ->get();


        return view('admin.odemeler.index', compact('odemeler'));
    }

    public function approve($id)
    {
        $transaction = AvukatTransaction::findOrFail($id);
        $transaction->status = 'completed';
        $transaction->save();

        // Avukat bakiyesine jeton ekle
        $transaction->user->increment('balance', $transaction->amount);

        return back()->with('success', 'İşlem onaylandı.');
    }

    public function reject($id)
    {
        $transaction = AvukatTransaction::findOrFail($id);
        $transaction->status = 'failed';
        $transaction->save();

        return back()->with('error', 'İşlem reddedildi.');
    }
}
