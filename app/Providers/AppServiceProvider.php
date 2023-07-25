<?php

namespace App\Providers;

use Accurate\Shipping\Client\Client;
use App\Models\Setting;
use Exception;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $setting = Setting::first();
            if ($setting) Client::init($setting->url, ['Authorization' => "Bearer $setting->token"]);
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
}
