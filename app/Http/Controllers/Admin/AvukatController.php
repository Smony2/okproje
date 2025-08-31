<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Avukat;
use App\Models\AvukatPuan;
use App\Models\IsPuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class AvukatController extends Controller
{
    public function index()
    {
        $avukatlar = Avukat::paginate(15);
        return view('admin.avukatlar.index', compact('avukatlar'));
    }

    public function show($id)
    {
        $avukat = Avukat::with(['isler'=>fn($q)=>$q->latest()->take(10),
            'transactions'=>fn($q)=>$q->latest()->take(5)])
            ->findOrFail($id);

        // Son 20 log kaydı
        $logs = Activity::forSubject($avukat)->latest()->limit(20)->get();

        // Değerlendirmeler (puanlar) örnek
        $puanlar = AvukatPuan::where('avukat_id', $avukat->id)
            ->latest()
            ->take(20)
            ->get();

        $avatar = Avatar::where('avukat_id',$id)->first();



        return view('admin.avukatlar.detay', compact('avukat','logs','puanlar','avatar'));
    }

    public function ban($id)
    {
        $avukat = Avukat::findOrFail($id);
        $avukat->update(['blokeli_mi'=>true]);
        return back()->with('success','Avukat bloke edildi.');
    }

    public function unban($id)
    {
        $avukat = Avukat::findOrFail($id);
        $avukat->update(['blokeli_mi'=>false]);
        return back()->with('success','Bloke kaldırıldı.');
    }

    public function create()
    {
        return view('admin.avukatlar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:avukats,email',
            'username'                  =>'required|string|unique:avukats,username',
            'phone'                  => 'nullable|string|max:20',
            'tc_no'                  => 'nullable|string|max:11',
            'baro_no'                => 'nullable|string|max:50',
            'baro_adi'               => 'nullable|string|max:255',
            'unvan'                  => 'nullable|string|max:255',
            'uzmanlik_alani'         => 'nullable|string|max:255',
            'dogum_tarihi'           => 'nullable|date',
            'cinsiyet'               => 'nullable|in:Erkek,Kadın,Diğer',
            'mezuniyet_universitesi' => 'nullable|string|max:255',
            'mezuniyet_yili'         => 'nullable|integer|min:1900|max:' . date('Y'),
            'adres'                  => 'nullable|string|max:1000',
            'notlar'                 => 'nullable|string|max:2000',
            'password'               => 'required|string|min:3|confirmed',
            'aktif_mi'               => 'nullable|boolean',
            'blokeli_mi'             => 'nullable|boolean',
        ]);

        Avukat::create([
            'name'                   => $request->name,
            'email'                  => $request->email,
            'username'                  => $request->username,
            'phone'                  => $request->phone,
            'tc_no'                  => $request->tc_no,
            'baro_no'                => $request->baro_no,
            'baro_adi'               => $request->baro_adi,
            'unvan'                  => $request->unvan,
            'uzmanlik_alani'         => $request->uzmanlik_alani,
            'dogum_tarihi'           => $request->dogum_tarihi,
            'cinsiyet'               => $request->cinsiyet,
            'mezuniyet_universitesi' => $request->mezuniyet_universitesi,
            'mezuniyet_yili'         => $request->mezuniyet_yili,
            'adres'                  => $request->adres,
            'notlar'                 => $request->notlar,
            'blokeli_mi'             => $request->has('blokeli_mi'),
            'password'               => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.avukatlar.index')
            ->with('success', 'Yeni avukat başarıyla eklendi.');
    }


    public function update(Request $request, $id)
    {
        $avukat = Avukat::findOrFail($id);

        // Eğer sadece şifre güncelleme talebi gelmişse
        if ($request->filled('update_password_only')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $avukat->password = Hash::make($request->password);
            $avukat->save();
            return back()->with('success', 'Şifre başarıyla güncellendi.');
        }

        // Genel profil bilgileri
        $request->validate([
            'name'                      => 'required|string|max:255',
            'email'                     => 'required|email|unique:avukats,email,' . $avukat->id,
            'username'                  =>'required|string|unique:avukats,username',
            'phone'                     => 'nullable|string|max:20',
            'tc_no'                     => 'nullable|string|max:11',
            'baro_no'                   => 'nullable|string|max:50',
            'baro_adi'                  => 'nullable|string|max:255',
            'unvan'                     => 'nullable|string|max:255',
            'uzmanlik_alani'            => 'nullable|string|max:255',
            'dogum_tarihi'              => 'nullable|date',
            'cinsiyet'                  => 'nullable|in:Erkek,Kadın,Diğer',
            'mezuniyet_universitesi'    => 'nullable|string|max:255',
            'mezuniyet_yili'            => 'nullable|integer|min:1900|max:' . date('Y'),
            'adres'                     => 'nullable|string|max:1000',
            'notlar'                    => 'nullable|string|max:2000',
        ]);

        $avukat->update($request->only([
            'name',
            'email',
            'phone',
            'tc_no',
            'baro_no',
            'baro_adi',
            'unvan',
            'username',
            'uzmanlik_alani',
            'dogum_tarihi',
            'cinsiyet',
            'mezuniyet_universitesi',
            'mezuniyet_yili',
            'adres',
            'notlar',
        ]));

        return back()->with('success', 'Avukat bilgileri güncellendi.');
    }

    public function destroy($id)
    {
        $avukat = Avukat::findOrFail($id);
        $avukat->delete();

        return redirect()->route('admin.avukatlar.index')->with('success', 'Avukat silindi.');
    }
}
