<?php
namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\IsTeklifi;
use Illuminate\Support\Facades\Auth;

class TeklifController extends Controller
{
    public function index()
    {
        $teklifler = IsTeklifi::with('isleri')
            ->where('katip_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('katip.tekliflerim.index', compact('teklifler'));
    }
}
