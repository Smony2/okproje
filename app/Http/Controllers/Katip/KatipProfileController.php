<?php

namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\AvukatPuan;
use App\Models\IsPuan;
use App\Models\KatipAvatar;
use App\Models\KatipPuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class KatipProfileController extends Controller
{
    public function editProfile()
    {
        // Şu anki oturum açmış kâtibi al
        $katip = auth('katip')->user()->load([
            'isler' => fn($q) => $q->latest()->take(10), // Son 10 iş
        ]);

        // Son 20 log kaydı
        $logs = Activity::forSubject($katip)->latest()->limit(20)->get();

        // Size yapılan yorumlar (avukatların kâtibe verdiği puanlar)
        $sizeYapilanPuanlar = AvukatPuan::whereHas('islem', fn($q) => $q->where('katip_id', $katip->id))
            ->with(['islem', 'avukat'])
            ->latest()
            ->take(20)
            ->get();

        // Sizin yaptığınız yorumlar (kâtibin avukatlara verdiği puanlar)
        $sizinYaptiginizPuanlar = KatipPuan::where('katip_id', $katip->id)
            ->with(['islem'])
            ->latest()
            ->take(20)
            ->get();

        // Avatar
        $avatar = KatipAvatar::where('katip_id', $katip->id)->first();

        return view('katip.profile', compact('katip', 'logs', 'sizeYapilanPuanlar', 'sizinYaptiginizPuanlar', 'avatar'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth('katip')->user();

        // 1) Validasyon
        $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'username'                => ['required', 'string', 'max:50', Rule::unique('katips','username')->ignore($user->id)],
            'email'                   => ['required', 'email', Rule::unique('katips','email')->ignore($user->id)],
            'phone'                   => ['nullable','string','max:20'],
            'tc_no'                   => ['nullable','digits:11'],
            'dogum_tarihi'            => ['nullable','date'],
            'cinsiyet'                => ['nullable', Rule::in(['Erkek','Kadın','Diğer'])],
            'mezuniyet_universitesi'  => ['nullable','string','max:255'],
            'mezuniyet_yili'          => ['nullable','integer','between:1900,'.(date('Y')+1)],
            'uzmanlik_alani'          => ['nullable','string','max:255'],
            'adres'                   => ['nullable','string'],
            'notlar'                  => ['nullable','string'],
            'avatar_url'              => ['nullable','image','max:1024'], // max 1MB
            'adliyeler'               => ['nullable','array'],
            'adliyeler.*'             => ['integer','exists:adliyeler,id'],
        ]);

        // 2) Alanları güncelle
        $user->name                   = $request->name;
        $user->username               = $request->username;
        $user->email                  = $request->email;
        $user->phone                  = $request->phone;
        $user->tc_no                  = $request->tc_no;
        $user->dogum_tarihi           = $request->dogum_tarihi;
        $user->cinsiyet               = $request->cinsiyet;
        $user->mezuniyet_okulu = $request->mezuniyet_universitesi;
        $user->mezuniyet_yili         = $request->mezuniyet_yili;
        $user->uzmanlik_alani         = $request->uzmanlik_alani;
        $user->adres                  = $request->adres;
        $user->notlar                 = $request->notlar;

        // 3) Avatar yükleme
        if ($request->hasFile('avatar_url')) {
            // eski avatar'ı sil (varsa)
            if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                Storage::disk('public')->delete($user->avatar_url);
            }
            // yeni avatar
            $path = $request->file('avatar_url')
                ->store('uploads/avatars', 'public');
            $user->avatar_url = $path;
        }

        $user->save();

        // 4) Pivot: çalıştığı adliyeler
        $user->adliyeler()->sync($request->input('adliyeler', []));

        // 5) Yönlendir ve mesaj
        return redirect()
            ->route('katip.profile.edit')
            ->with('success', 'Profiliniz başarıyla güncellendi.');
    }


    public function changePasswordForm()
    {
        return view('katip.sifredegistir');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifre yanlış.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('avukat.settings')->with('success', 'Şifre başarıyla güncellendi.');
    }
}
