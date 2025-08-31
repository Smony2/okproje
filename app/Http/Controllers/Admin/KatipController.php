<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adliye;
use App\Models\Avatar;
use App\Models\Avukat;
use App\Models\IsPuan;
use App\Models\Katip;
use App\Models\KatipAvatar;
use App\Models\KatipPuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class KatipController extends Controller
{
    public function index()
    {
        $katipler = Katip::paginate(15); // 20'şer listeleyelim
        return view('admin.katipler.index', compact('katipler'));
    }

    public function show($id)
    {
        $katip = Katip::with([
            // Son 10 işi al
            'isler'      => fn($q) => $q->latest()->take(10),
            // Tüm adliyeleri al (istersen orderBy ekleyebilirsin)
            'adliyeler'  => fn($q) => $q->orderBy('ad')
        ])->findOrFail($id);

        // Son 20 log kaydı
        $logs = Activity::forSubject($katip)
            ->latest()
            ->limit(20)
            ->get();

        // Son 20 değerlendirme
        $puanlar = KatipPuan::where('katip_id', $katip->id)
            ->latest()
            ->take(20)
            ->get();

        // Avatar (varsa)
        $avatar = KatipAvatar::where('katip_id', $id)->first();
        $tumAdliyeler = Adliye::orderBy('ad')->get();


        return view('admin.katipler.detay', compact('katip','logs','puanlar','avatar','tumAdliyeler'));
    }

    public function ban($id)
    {
        $avukat = Katip::findOrFail($id);
        $avukat->update(['blokeli_mi'=>true]);
        return back()->with('success','Katip bloke edildi.');
    }

    public function unban($id)
    {
        $avukat = Katip::findOrFail($id);
        $avukat->update(['blokeli_mi'=>false]);
        return back()->with('success','Bloke kaldırıldı.');
    }

    public function create()
    {
        return view('admin.katipler.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:avukats,email',
            'username'                  =>'required|string|unique:avukats,username',
            'phone'                  => 'nullable|string|max:20',
            'tc_no'                  => 'nullable|string|max:11',
            'unvan'                  => 'nullable|string|max:255',
            'uzmanlik_alani'         => 'nullable|string|max:255',
            'dogum_tarihi'           => 'nullable|date',
            'cinsiyet'               => 'nullable|in:Erkek,Kadın,Diğer',
            'mezuniyet_universitesi' => 'nullable|string|max:255',
            'mezuniyet_yili'         => 'nullable|integer|min:1900|max:' . date('Y'),
            'adres'                  => 'nullable|string|max:1000',
            'notlar'                 => 'nullable|string|max:2000',
            'password'               => 'required|string|min:5|confirmed',
            'aktif_mi'               => 'nullable|boolean',
            'blokeli_mi'             => 'nullable|boolean',
        ]);

        Katip::create([
            'name'                   => $request->name,
            'email'                  => $request->email,
            'username'                  => $request->username,
            'phone'                  => $request->phone,
            'tc_no'                  => $request->tc_no,
            'unvan'                  => $request->unvan,
            'uzmanlik_alani'         => $request->uzmanlik_alani,
            'dogum_tarihi'           => $request->dogum_tarihi,
            'cinsiyet'               => $request->cinsiyet,
            'mezuniyet_okulu' => $request->mezuniyet_universitesi,
            'mezuniyet_yili'         => $request->mezuniyet_yili,
            'adres'                  => $request->adres,
            'notlar'                 => $request->notlar,
            'blokeli_mi'             => $request->has('blokeli_mi'),
            'password'               => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.katipler.index')
            ->with('success', 'Yeni katip başarıyla eklendi.');
    }


    public function update(Request $request, $id)
    {
        $katip = Katip::findOrFail($id);

        // Eğer sadece şifre güncelleme talebi gelmişse
        if ($request->filled('update_password_only')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $katip->password = Hash::make($request->password);
            $avukat->save();
            return back()->with('success', 'Şifre başarıyla güncellendi.');
        }

        // Genel profil bilgileri
        $request->validate([
            'name'                      => 'required|string|max:255',
            'email'                     => 'required|email|unique:avukats,email,' . $katip->id,
            'username'                  =>'required|string|unique:avukats,username',
            'phone'                     => 'nullable|string|max:20',
            'tc_no'                     => 'nullable|string|max:11',
            'unvan'                     => 'nullable|string|max:255',
            'uzmanlik_alani'            => 'nullable|string|max:255',
            'dogum_tarihi'              => 'nullable|date',
            'cinsiyet'                  => 'nullable|in:Erkek,Kadın,Diğer',
            'mezuniyet_universitesi'    => 'nullable|string|max:255',
            'mezuniyet_yili'            => 'nullable|integer|min:1900|max:' . date('Y'),
            'adres'                     => 'nullable|string|max:1000',
            'notlar'                    => 'nullable|string|max:2000',
        ]);

        $katip->update($request->only([
            'name',
            'email',
            'phone',
            'tc_no',
            'unvan',
            'username',
            'uzmanlik_alani',
            'dogum_tarihi',
            'cinsiyet',
            'mezuniyet_okulu',
            'mezuniyet_yili',
            'adres',
            'notlar',
        ]));

        return back()->with('success', 'Katip bilgileri güncellendi.');
    }

    public function destroy($id)
    {
        $avukat = Katip::findOrFail($id);
        $avukat->delete();

        return redirect()->route('admin.katipler.index')->with('success', 'Avukat silindi.');
    }

    public function syncAdliyeler(Request $request, $id)
    {
        $katip = Katip::findOrFail($id);
        $request->validate([
            'adliyeler'   => 'array',
            'adliyeler.*' => 'exists:adliyeler,id',
        ]);

        // Girilen adliyeleri pivot tablosuna yansıt
        $katip->adliyeler()->sync($request->adliyeler ?? []);

        return back()->with('success', 'Adliyeler güncellendi.');
    }


}
