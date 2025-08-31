<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'avukat':
                        return redirect()->route('avukat.dashboard');
                    case 'katip':
                        return redirect()->route('katip.dashboard');
                    default:
                        return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
