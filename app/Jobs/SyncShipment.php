<?php

namespace App\Jobs;

use App\Http\Controllers\Client\Imile\Customer\Auth;
use  App\Http\Controllers\Client\Imile\Customer\Order;
use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncShipment implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * number of retries before asign job as failed
     *
     * @var integer
     */
    public $tries = 3;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payloadShipment = unserialize($this->job->payload()['data']['command'])->shipment;
        $try = 0;
        info(++$try . "job for sync orders");
        if (Shipment::where('shipment_id', $payloadShipment['id'])->whereNull('order_code')->exists()) {
            $token = Auth::auth();
            Order::syncOrders($this->shipment, $token);
        }
    }
}
