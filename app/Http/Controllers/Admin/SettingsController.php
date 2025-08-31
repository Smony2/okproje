<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        $settings = Setting::first();

        // Logo işleme
        if ($request->hasFile('logo')) {
            // Eski logoyu sil
            if ($settings->logoresimyol && file_exists(public_path('uploads/' . $settings->logoresimyol))) {
                unlink(public_path('uploads/' . $settings->logoresimyol));
            }

            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads'), $logoName);
            $settings->logoresimyol = $logoName;
        }

        // Favicon işleme
        if ($request->hasFile('favicon')) {
            // Eski favicon'u sil
            if ($settings->faviconyol && file_exists(public_path('uploads/' . $settings->faviconyol))) {
                unlink(public_path('uploads/' . $settings->faviconyol));
            }

            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads'), $faviconName);
            $settings->faviconyol = $faviconName;
        }

        $settings->save();

        Log::info('Site ayarları güncellendi', [
            'user_id' => auth()->id(),
            'settings' => $settings->toArray()
        ]);

        return redirect()->route('admin.site-settings.index')
            ->with('success', 'Ayarlar başarıyla güncellendi.');
    }
} 