<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity; // Spatie modelini kullandık

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::latest()->paginate(20); // Son işlemleri çekiyoruz
        return view('admin.activity_log.index', compact('logs'));
    }

    public function show($id)
    {
        $log = Activity::findOrFail($id);
        return view('admin.activity_log.show', compact('log'));
    }
}
