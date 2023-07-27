<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\Accurate\DeliveryAgent\Shipment;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call([Shipment::class, 'syncShipments'])
            ->everyMinute()
            ->onSuccess(function () {
                info("\n The task succeeded");
            })
            ->onFailure(function () {
                // TODO make a table that holds the try error into it 
                info("\n  The task failed");
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
