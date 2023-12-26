<?php

namespace App\Http\Controllers\Provider\Accurate\Customer;

use Accurate\Shipping\Enums\Fields\Core\DropDownField;
use Accurate\Shipping\Enums\Fields\ShipmentField;
use Accurate\Shipping\Models\Inputs\Fields\Delivered\FullDeliveredType;
use Accurate\Shipping\Models\Inputs\Fields\DeliveredField;
use Accurate\Shipping\Models\Inputs\Fields\HoldedField;
use Accurate\Shipping\Models\Inputs\Fields\Return\FullyDueField;
use Accurate\Shipping\Models\Inputs\Fields\ReturnField;
use Accurate\Shipping\Models\Inputs\UpdateStatus;
use Accurate\Shipping\Services\Shipment as ServicesShipment;
use App\Enums\WebHookStatusCode;
use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Zone;
use Exception;
use Illuminate\Http\Request;

class Order extends Controller
{
    /**
     * 
     * @param array $shipment
     * @param string $token
     * @return void
     */
    static function syncOrders($shipment, $token)
    {
        // bring shipments from the user to accurate
    }

    /**
     * @param Request $request
     * @return void
     */
    function callback(Request $request)
    {
        $data = $request->all();
        // 
        $output = [
            ShipmentField::ID,
            ShipmentField::CODE,
            ShipmentField::status([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME,
            ]),
        ];

        $notes = "This is from action webhook callback.\n" . @$data['lastProblemStatus'] ?? "";
        $shipmentId = Shipment::where('order_code', $data['orderCode'])->first()?->shipment_id;
        if (!$shipmentId) return response('error', 500);

        try {
            $input = null;
            match ($data['orderStatus']) {
                WebHookStatusCode::DELIVERED  => $input = new UpdateStatus(
                    id: $shipmentId,
                    notes: $notes,
                    deliveredField: new DeliveredField(deliveryType: new FullDeliveredType()),
                ),
                WebHookStatusCode::CANCELED => $input = new UpdateStatus(
                    id: $shipmentId,
                    notes: $notes,
                    returnField: new ReturnField(returnField: new FullyDueField()),
                ),
                WebHookStatusCode::RE_SCHEDULE  => $input = new UpdateStatus(
                    id: $shipmentId,
                    notes: $notes,
                    holdToRedeliver: new HoldedField(deliveryDate: null),
                ),
                default => throw new Exception("unhandeled orderStatus: {$data['orderStatus']}")
            };

            $result = (new ServicesShipment())->updateShipmentStatus($input, $output);
            return $result;
        } catch (\Exception $e) {
            info($e->getMessage());
            return response('error', 500);
        }
    }

    /**
     * TODO handle every status codes
     * Order Type: (Only one of the following types can be selected)
     * 100: Shipping Orders
     * 200: Return order
     * 400: Refund order
     * 800: Forward order
     *
     * @return string
     */
    static function getOrderTypeCode(): string
    {
        return "100";
    }

    /**
     * 
     * Payment method: (only one of the following types can beselected)
     * 100: PPD(Prepaid) === CASH in accurate
     * 200: Cash (Cash On Delivery) === COLC in accurate 
     * @param [type] $paymentType
     * @return string
     */
    static function getPaymentMethod($paymentType): string
    {
        return  match ($paymentType) {
            "CASH" => "100",
            "COLC" => "200",
        };
    }

    /**
     * TODO test this logic well
     * add the logic to get the code of the area
     * puth default here
     * @param [type] $subZoneId
     * @return string
     */
    static function getArea($subZoneId): string
    {
        $subzone = Zone::where('zone_id', $subZoneId)->whereNotNull('parent_id')->first();
        return $subzone->mapped_zone ?? "";
    }

    /**
     * TODO test this logic well
     *
     * puth default here
     *
     * @param [type] $zoneId
     * @return string
     */
    static function getCity($zoneId): string
    {
        $subzone = Zone::where('zone_id', $zoneId)->whereNull('parent_id')->first();
        return $subzone->mapped_zone ?? "";
    }

    /**
     * it will be used when needed
     * add the logic to get the correct country
     * 
     * @return string
     */
    static function getCountry(): string
    {
        return "KSA";
    }
}
