<?php
namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserStatusUpdated;

class KatipAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('katip.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('katip')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('katip')->user();
            $user->update([
                'is_active' => 1,
                'last_active_at' => now(),
            ]);
            event(new UserStatusUpdated($user->id, 'Katip', true, now()));

            $request->session()->regenerate();
            return redirect()->route('katip.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email veya şifre yanlış.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('katip')->user();
        if ($user) {
            $user->update([
                'is_active' => 0,
                'last_active_at' => now(),
            ]);
            event(new UserStatusUpdated($user->id, 'Katip', false, now()));
        }

        Auth::guard('katip')->logout();
        $request->session()->forget('katip_login');
        $request->session()->regenerateToken();

        return redirect()->route('katip.login');
    }
}