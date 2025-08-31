<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{



    public function edit()
    {
        $avukat = auth('avukat')->user();
        $avatar = $avukat->avatar;
        $puanlar = $avukat->puanlar()->latest()->get();
        $ortPuan = round($puanlar->avg('puan'), 2);
        $isSayisi = $avukat->isler()->count();
        $iptalSayisi = $avukat->isler()->where('durum', 'iade')->count();

        return view('avukat.profile', compact('avukat', 'avatar', 'puanlar', 'ortPuan', 'isSayisi', 'iptalSayisi'));
    }



    public function settings()
    {
        return view('admin.settings');
    }

    
}
