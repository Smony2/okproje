<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adliye;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;    // ← add this line
use Illuminate\Support\Str;


class AdliyeController extends Controller
{
    // Listeleme
    public function index()
    {
        $adliyeler = Adliye::orderByDesc('created_at')->paginate(15);
        return view('admin.adliyeler.index', compact('adliyeler'));
    }

    // Yeni Adliye Formu
    public function create()
    {
        return view('admin.adliyeler.create');
    }

    // Yeni Adliye Kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'ad'          => 'required|string|max:255',
            'il'          => 'required|string|max:100',
            'ilce'        => 'required|string|max:100',
            'adres'       => 'nullable|string',
            'telefon'     => 'nullable|string',
            'konum_linki' => 'nullable|string',
            'aktif_mi'    => 'required|boolean',
            'resimyol'    => 'nullable|image|max:2048', // jpeg/png/gif, up to 2MB
        ]);

        $data = $request->all();

        // Handle new upload
        if ($file = $request->file('resimyol')) {
            // Generate a unique filename
            $filename = Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move to public/uploads
            $file->move(public_path('uploads'), $filename);

            // Store relative path
            $data['resimyol'] = 'uploads/' . $filename;
        }

        Adliye::create($data);

        return redirect()->route('admin.adliyeler.index')->with('success', 'Adliye başarıyla eklendi.');
    }

    // Adliye Düzenleme Formu
    public function edit($id)
    {
        $adliye = Adliye::findOrFail($id);
        return view('admin.adliyeler.edit', compact('adliye'));
    }

    // Güncelleme
    public function update(Request $request, $id)
    {
        $request->validate([
            'ad'          => 'required|string|max:255',
            'il'          => 'required|string|max:100',
            'ilce'        => 'nullable|string|max:100',
            'adres'       => 'nullable|string',
            'telefon'     => 'nullable|string',
            'konum_linki' => 'nullable|url',
            'aktif_mi'    => 'boolean',
            'resimyol'    => 'nullable|image|max:2048', // jpeg/png/gif, up to 2MB
        ]);

        $adliye = Adliye::findOrFail($id);

        // handle new upload
        if ($file = $request->file('resimyol')) {
            // generate a unique filename
            $filename = Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();

            // move to public/uploads
            $file->move(public_path('uploads'), $filename);

            // delete old if exists
            if ($adliye->resimyol && file_exists(public_path($adliye->resimyol))) {
                unlink(public_path($adliye->resimyol));
            }

            // store relative path
            $adliye->resimyol = 'uploads/' . $filename;
        }

        // update the rest of fields
        $adliye->ad          = $request->ad;
        $adliye->il          = $request->il;
        $adliye->ilce        = $request->ilce;
        $adliye->adres       = $request->adres;
        $adliye->telefon     = $request->telefon;
        $adliye->konum_linki = $request->konum_linki;
        $adliye->aktif_mi    = $request->has('aktif_mi');

        $adliye->save();

        return redirect()
            ->route('admin.adliyeler.index')
            ->with('success', 'Adliye başarıyla güncellendi.');
    }

    // Silme
    public function destroy($id)
    {
        $adliye = Adliye::findOrFail($id);
        $adliye->delete();

        return redirect()->route('admin.adliyeler.index')->with('success', 'Adliye silindi.');
    }
}
