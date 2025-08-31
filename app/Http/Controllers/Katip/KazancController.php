<?php
namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\KatipTransaction;
use Illuminate\Support\Facades\Auth;

class KazancController extends Controller
{
    public function index()
    {
        $katipId = Auth::id();

        $kazanclar = KatipTransaction::where('katip_id', $katipId)
            ->where('type', 'kazanc')
            ->latest()
            ->paginate(20);

        return view('katip.kazanclar.index', compact('kazanclar'));
    }
}
