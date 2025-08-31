<?php

namespace App\Http\Controllers\Avukat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvukatAuthController extends Controller
{
    public function showLoginForm()
    {

        return view('avukat.auth.login');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('avukat')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('avukat.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email veya şifre yanlış.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::guard('avukat')->logout();

        $request->session()->forget('avukat_login');

        // İsteğe bağlı: sadece token yenile
        $request->session()->regenerateToken();

        return redirect()->route('avukat.login');
    }
}
