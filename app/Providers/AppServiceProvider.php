<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Accurate\Shipping\Client\Client;
use Accurate\Shipping\Services\Shipment as ShipmentService;
use App\Models\Client as ModelsClient;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ShipmentService::class, function ($app) {
            return new ShipmentService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // try {
        //     $client = ModelsClient::first();
        //     if ($client) Client::init($client->url, ['Authorization' => "Bearer $client->token"]);
        // } catch (Exception $e) {
        //     info($e->getMessage());
        // }

        // $this->app->bind('client', function ($app, $parameters) {
        //     $id = $parameters['id'] ?? null;

        //     if ($id !== null) {
        //         $setting = ModelsClient::find($id);
        //         if ($setting) {
        //             Client::init($setting->url, ['Authorization' => "Bearer $setting->token"]);
        //             return "success";
        //         }
        //     }

        //         return null; // Default value or handle when settings are not found
        //     });
        // }
    }
}
