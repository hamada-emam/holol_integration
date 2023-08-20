<?php

namespace App\Http\Controllers\Client\Imile\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Accurate\DeliveryAgent\Shipment;
use Illuminate\Http\Response;

class Order extends Controller
{
    /**
     * Create a new OrderService instance.
     */
    public function __construct(public Shipment $shipment)
    {
    }

    /**
     * Handle imile WebHook 
     *
     * @param Request $request
     * @return void
     */
    function callback(Request $request)
    {
        try {
            $data = $request->all();
            return $this->shipment->updateShipmentStatus($data);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response('error', 500);
        }
    }
}
