<?php
namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\IsTeslimat;
use Illuminate\Support\Facades\Auth;

class TeslimController extends Controller
{
    public function index()
    {
        $teslimler = IsTeslimat::with('isleri')
            ->where('katip_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('katip.teslimlerim.index', compact('teslimler'));
    }
}
