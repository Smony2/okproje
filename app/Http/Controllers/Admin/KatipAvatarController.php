<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KatipAvatar;
use App\Models\Katip;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class KatipAvatarController extends Controller
{

    public function index()
    {
        $atanmis     = KatipAvatar::with('katip')
            ->whereNotNull('katip_id')
            ->latest()
            ->get();

        $atanmamis   = KatipAvatar::whereNull('katip_id')
            ->latest()
            ->get();

        return view('admin.katip_avatarlar.index', compact('atanmis','atanmamis'));
    }



    public function create()
    {
        $katipler = Katip::doesntHave('avatar')->get();
        return view('admin.katip_avatarlar.create', compact('katipler'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'katip_id' => 'nullable|exists:katips,id|unique:katip_avatars,katip_id',
        ]);

        // Dosya ve uzantı
        $file = $request->file('path');
        $extension = $file->getClientOriginalExtension();

        // Rastgele benzersiz dosya adı
        $filename = Str::uuid() . '.' . $extension;

        // Hedef klasör
        $destinationPath = public_path('upload/katip_avatars');

        // Klasör yoksa oluştur
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Dosyayı taşı
        $file->move($destinationPath, $filename);

        $path = 'upload/katip_avatars/' . $filename;

        // DB kaydı
        KatipAvatar::create([
            'path' => $path,
            'katip_id' => $request->katip_id,
        ]);

        return redirect()->route('admin.katip-avatarlar.index')->with('success', 'Katip avatarı başarıyla eklendi.');
    }

    public function edit($id)
    {
        $avatar = KatipAvatar::findOrFail($id);
        $katipler = Katip::doesntHave('avatar')->orWhere('id', $avatar->katip_id)->get();
        return view('admin.katip_avatarlar.edit', compact('avatar', 'katipler'));
    }

    public function update(Request $request, $id)
    {
        $avatar = KatipAvatar::findOrFail($id);

        $request->validate([
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'katip_id' => 'nullable|exists:katips,id|unique:katip_avatars,katip_id,' . $avatar->id,
        ]);

        if ($request->hasFile('path')) {
            if (file_exists(public_path($avatar->path))) {
                unlink(public_path($avatar->path));
            }
            $filename = time().'_'.$request->file('path')->getClientOriginalName();
            $request->file('path')->move(public_path('upload/katip_avatars'), $filename);
            $avatar->path = 'upload/katip_avatars/' . $filename;
        }

        $avatar->katip_id = $request->katip_id;
        $avatar->save();

        return redirect()->route('admin.katip-avatarlar.index')->with('success', 'Avatar güncellendi.');
    }

    public function destroy($id)
    {
        $avatar = KatipAvatar::findOrFail($id);

        if (file_exists(public_path($avatar->path))) {
            unlink(public_path($avatar->path));
        }

        $avatar->delete();

        return back()->with('success', 'Avatar silindi.');
    }
}
