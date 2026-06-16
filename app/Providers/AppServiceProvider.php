<?php

namespace App\Providers;

use App\Models\BrandSetting;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $view->with('brand', BrandSetting::first());
            } catch (\Exception $e) {
                $view->with('brand', null);
            }
            try {
                $view->with('unreadNotifikasi', Notifikasi::unreadCount());
            } catch (\Exception $e) {
                $view->with('unreadNotifikasi', 0);
            }
        });
    }
}
