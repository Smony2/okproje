<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Events\UserStatusUpdated;
use Carbon\Carbon;

class UpdateLastActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('avukat')->user();
        $user->update([
            'is_active' => 1,
            'last_active_at' => now(),
        ]);


        return $next($request);
    }


}