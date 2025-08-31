<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatipRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('katip')->user();

        if (!$user) {
            abort(403, 'Giriş yapmalısınız.');
        }

        // Eğer adminin sahip olduğu rollerden biri eşleşiyorsa izin ver
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Hiçbiri eşleşmiyorsa
        abort(403, 'Bu sayfaya erişim izniniz yok.');
    }
}
