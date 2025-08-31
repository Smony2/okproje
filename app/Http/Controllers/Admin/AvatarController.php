<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Avukat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AvatarController extends Controller
{
    public function index()
    {
        $atanmis     = Avatar::with('avukat')
            ->whereNotNull('avukat_id')
            ->latest()
            ->get();

        $atanmamis   = Avatar::whereNull('avukat_id')
            ->latest()
            ->get();

        return view('admin.avatarlar.index', compact('atanmis','atanmamis'));
    }

    public function create()
    {
        $avukatlar = Avukat::doesntHave('avatar')->get();
        return view('admin.avatarlar.create', compact('avukatlar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'path' => 'required|image|mimes:jpeg,png,jpg,gif|max:4048',
            'avukat_id' => 'nullable|exists:avukats,id|unique:avatars,avukat_id',
        ]);

        // Dosya ve uzantı
        $file = $request->file('path');
        $extension = $file->getClientOriginalExtension();

        // Rastgele benzersiz isim
        $filename = Str::uuid() . '.' . $extension;

        // Hedef klasör
        $destinationPath = public_path('upload/avatars');

        // Klasör yoksa oluştur
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Dosyayı taşı
        $file->move($destinationPath, $filename);

        $path = 'upload/avatars/' . $filename;

        // DB kaydı
        Avatar::create([
            'path' => $path,
            'avukat_id' => $request->avukat_id,
        ]);

        return redirect()->route('admin.avatarlar.index')->with('success', 'Avatar başarıyla eklendi.');
    }

    public function edit($id)
    {
        $avatar = Avatar::findOrFail($id);

        // ❶ Avatarı olmayanlar  + ❷  hâlihazırda bu avatara sahip avukat
        $avukatlar = Avukat::doesntHave('avatar')
            ->orWhere('id', $avatar->avukat_id)
            ->get();

        return view('admin.avatarlar.edit', compact('avatar', 'avukatlar'));
    }

    public function update(Request $request, $id)
    {
        $avatar = Avatar::findOrFail($id);

        $request->validate([
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'avukat_id' => 'nullable|exists:avukats,id|unique:avatars,avukat_id,' . $avatar->id,
        ]);

        if ($request->hasFile('path')) {
            if (file_exists(public_path($avatar->path))) {
                unlink(public_path($avatar->path));
            }

            $filename = time() . '_' . $request->file('path')->getClientOriginalName();
            $request->file('path')->move(public_path('upload/avatars'), $filename);

            $avatar->path = 'upload/avatars/' . $filename;
        }

        $avatar->avukat_id = $request->avukat_id;
        $avatar->save();

        return redirect()->route('admin.avatarlar.index')->with('success', 'Avatar güncellendi.');
    }
    public function destroy($id)
    {
        $avatar = Avatar::findOrFail($id);

        // Dosya varsa sil
        if ($avatar->path && file_exists(public_path($avatar->path))) {
            unlink(public_path($avatar->path));
        }

        // Veritabanından kaydı sil
        $avatar->delete();

        return back()->with('success', 'Avatar silindi.');
    }
}
