<?php

namespace App\Providers;

use App\Models\Avatar;
use App\Models\KatipAvatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        View::composer('avukat.*', function ($view) {
            $user = Auth::guard('avukat')->user();

            $avatar = null;
            if ($user) {
                $avatar = Avatar::where('avukat_id', $user->id)->first();
            }

            $view->with('avukatAvatar', $avatar);
        });


        View::composer('katip.*', function ($view) {
            $user = Auth::guard('katip')->user();

            $avatar = null;
            if ($user) {
                $avatar = KatipAvatar::where('katip_id', $user->id)->first();
            }

            $view->with('katipAvatar', $avatar);
        });

        // Tüm view'lara site ayarlarını gönder
        View::composer('*', function ($view) {
            $settings = Setting::first();
            $view->with('settings', $settings);
        });
    }
}
