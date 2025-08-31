<?php

namespace App\Http\Controllers\Avukat;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\AvukatPuan;
use App\Models\IsPuan;
use App\Models\KatipPuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class AvukatProfileController extends Controller
{

    public function editProfile()
    {
        // Şu anki oturum açmış avukatı al
        $avukat = auth('avukat')->user()->load([
            'isler' => fn($q) => $q->latest()->take(10), // Son 10 iş
            'transactions' => fn($q) => $q->latest()->take(5), // Son 5 işlem
        ]);

        // Size yapılan yorumlar (kâtibin avukata verdiği puanlar)
        $sizeYapilanPuanlar = KatipPuan::whereHas('islem', fn($q) => $q->where('avukat_id', $avukat->id))
            ->with(['islem', 'katip'])
            ->latest()
            ->take(20)
            ->get();

        // Sizin yaptığınız yorumlar (avukatın kâtibe verdiği puanlar)
        $sizinYaptiginizPuanlar = AvukatPuan::where('avukat_id', $avukat->id)
            ->with(['islem'])
            ->latest()
            ->take(20)
            ->get();

        // Avatar
        $avatar = Avatar::where('avukat_id', $avukat->id)->first();


        return view('avukat.profile', compact('avukat', 'sizeYapilanPuanlar', 'sizinYaptiginizPuanlar', 'avatar'));
    }
    public function updateProfile(Request $request)
    {
        $user = auth('avukat')->user();

        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:avukats,email,'.$user->id,
            'phone'                  => 'nullable|string|max:30',
            'tc_no'                  => 'nullable|string|max:20',
            'baro_no'                => 'nullable|string|max:50',
            'baro_adi'               => 'nullable|string|max:100',
            'unvan'                  => 'nullable|string|max:100',
            'dogum_tarihi'           => 'nullable|date',
            'cinsiyet'               => 'nullable|string|in:Erkek,Kadın,Diğer',
            'mezuniyet_universitesi' => 'nullable|string|max:150',
            'mezuniyet_yili'         => 'nullable|integer|min:1900|max:'.(date('Y')+1),
            'uzmanlik_alani'         => 'nullable|string|max:255',
            'adres'                  => 'nullable|string|max:500',
            'notlar'                 => 'nullable|string|max:500',
            'avatar_url'             => 'nullable|image|max:2048',
        ]);

        // handle avatar upload
        if ($request->hasFile('avatar_url')) {
            // delete old if exists
            if ($user->avatar_url) {
                Storage::disk('public')->delete(Str::after($user->avatar_url, 'storage/'));
            }
            $path = $request->file('avatar_url')->store('avatars', 'public');
            $data['avatar_url'] = 'storage/'.$path;
        }


        $user->update($data);

        return back()->with('success', 'Profilinifffz başarıyla güncellendi.');
    }

    public function changePasswordForm()
    {
        return view('avukat.sifredegistir');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:2', 'confirmed'],
        ]);

        $user = auth('avukat')->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Mevcut şifre yanlış.'])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Şifre başarıyla güncellendi.');
    }


    public function index()
    {
        $avatars = Avatar::whereNull('avukat_id')->orWhere('avukat_id', Auth::id())->get();
        return view('avukat.avatar_sec', compact('avatars'));
    }

    public function sec(Request $request)
    {
        $request->validate([
            'avatar_id' => 'required|exists:avatars,id',
        ]);

        $avatar = Avatar::findOrFail($request->avatar_id);

        // Başkasına atanmışsa izin verme
        if ($avatar->avukat_id && $avatar->avukat_id != Auth::id()) {
            return back()->withErrors(['avatar_id' => 'Bu avatar başkasına atanmış.']);
        }

        // Daha önce atanmış bir avatar varsa kaldır
        Avatar::where('avukat_id', Auth::id())->update(['avukat_id' => null]);

        $avatar->avukat_id = Auth::id();
        $avatar->save();

        return redirect()->route('avukat.avatar-sec')->with('success', 'Avatar başarıyla güncellendi.');
    }
}
