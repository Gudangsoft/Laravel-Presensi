<?php

namespace App\Providers;

use App\Models\BrandSetting;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Cache;
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
                $brand = Cache::remember('brand_setting', 3600, fn() => BrandSetting::first());
                $view->with('brand', $brand);
            } catch (\Throwable $e) {
                $view->with('brand', null);
            }
            try {
                $count = Cache::remember('unread_notifikasi', 60, fn() => Notifikasi::unreadCount());
                $view->with('unreadNotifikasi', $count);
            } catch (\Throwable $e) {
                $view->with('unreadNotifikasi', 0);
            }
        });
    }
}
