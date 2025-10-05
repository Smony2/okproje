<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adliye;
use App\Models\Isler;
use App\Models\Katip;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KatipStatsController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled('date_from') ? Carbon::parse($request->input('date_from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to   = $request->filled('date_to')   ? Carbon::parse($request->input('date_to'))  ->endOfDay()   : now()->endOfDay();

        $adliyeId = $request->filled('adliye_id') ? (int) $request->input('adliye_id') : null;
        $katipId  = $request->filled('katip_id')  ? (int) $request->input('katip_id')  : null;
        $q        = trim((string) $request->get('q'));

        $builder = Isler::query()
            ->selectRaw('katip_id, COUNT(*) as job_count, MAX(is_tamamlandi_at) as last_completed_at')
            ->whereNotNull('katip_id')
            ->where('durum', 'tamamlandi')
            ->whereBetween('is_tamamlandi_at', [$from, $to]);

        if ($adliyeId) {
            $builder->where('adliye_id', $adliyeId);
        }
        if ($katipId) {
            $builder->where('katip_id', $katipId);
        }
        if ($q !== '') {
            $builder->whereIn('katip_id', function($sub) use ($q) {
                $sub->from('katips')->select('id')
                    ->where('name', 'like', "%$q%")
                    ->orWhere('username', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        $records = $builder
            ->groupBy('katip_id')
            ->orderByDesc('job_count')
            ->paginate(20)
            ->withQueryString();

        $katipMap = Katip::whereIn('id', $records->pluck('katip_id')->filter()->all())
            ->get(['id', 'name', 'username'])
            ->keyBy('id');

        $adliyeler = Adliye::orderBy('ad')->get(['id','ad']);
        $katipler  = Katip::orderBy('name')->get(['id','name']);

        return view('admin.istatistik.katipler', compact(
            'records', 'katipMap', 'adliyeler', 'katipler', 'from', 'to', 'adliyeId', 'katipId', 'q'
        ));
    }
}


