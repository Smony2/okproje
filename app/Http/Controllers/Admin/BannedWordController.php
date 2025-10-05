<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedWord;
use App\Services\MessageFilterService;
use Illuminate\Http\Request;

class BannedWordController extends Controller
{
    public function index()
    {
        $words = BannedWord::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.yasakli-kelimeler.index', compact('words'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word',
        ]);

        BannedWord::create([
            'word' => strtolower(trim($request->word)),
            'is_active' => true,
        ]);

        // Cache'i temizle
        MessageFilterService::clearCache();

        return back()->with('success', 'Yasaklı kelime başarıyla eklendi.');
    }

    public function destroy($id)
    {
        $word = BannedWord::findOrFail($id);
        $word->delete();

        // Cache'i temizle
        MessageFilterService::clearCache();

        return back()->with('success', 'Yasaklı kelime silindi.');
    }

    public function toggle($id)
    {
        $word = BannedWord::findOrFail($id);
        $word->is_active = !$word->is_active;
        $word->save();

        // Cache'i temizle
        MessageFilterService::clearCache();

        $status = $word->is_active ? 'aktif' : 'pasif';
        return back()->with('success', "Kelime {$status} hale getirildi.");
    }
}

