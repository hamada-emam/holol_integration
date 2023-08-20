<?php

namespace App\Services\Accurate\DeliveryAgent;

use Accurate\Shipping\Models\Inputs\Fields\Delivered\FullDeliveredType;
use Accurate\Shipping\Models\Inputs\Fields\DeliveredField;
use Accurate\Shipping\Models\Inputs\Fields\HoldedField;
use Accurate\Shipping\Models\Inputs\Fields\Return\FullyDueField;
use Accurate\Shipping\Models\Inputs\Fields\ReturnField;
use Accurate\Shipping\Models\Inputs\UpdateStatus;
use Accurate\Shipping\Services\Shipment as ShipmentServices;
use App\Enums\WebHookStatusCode;
use Accurate\Shipping\Enums\Fields\Core\DropDownField;
use Accurate\Shipping\Enums\Fields\ShipmentField;
use App\Models\Shipment as ShipmentModel;
use App\Traits\InitAccurate;
use Exception;
use Illuminate\Http\Request;

class Shipment
{
    use InitAccurate;

    /**
     * Create a new OrderService instance.
     * @var ShipmentService $shipment
     */
    public function __construct(Request $request)
    {
        $this->initClient($request->route('secret_key'));
    }

    /**
     * 
     * get shipment id with orderCode 
     * $response['orderCode'];
     * update the shipment via this status
     * $response['orderStatus'];
     * will despatched via webhook
     *  here will back the process to make shipment status updated
     *  serialize the obj and compair it with the saved obj into the shipment table
     *  check new status with mapped statess and get the equevilant status for the shipment
     * it will receive the the status code to me  
     * $statusCode = match ($code) {
     *     WebHookStatusCode::RE_SCHEDULE => 'HTR',
     *     WebHookStatusCode::DELIVERED => 'DTR',
     *     WebHookStatusCode::CANCELED => 'RTS',
     * };
     * it retries 20 times from the side of imile 
     * $shipment=Shipment::where('order_code',$request->);
     * which will come from webhook is => orderStatus
     * Shipment::syncStatus();
     * send update status for $shipmentId and $statusCode
     * return respnse status code
     *
     * TODO we need order status and order code to get the shipment id from the table 
     * TODO make default case that read unmapped states and put it into table some where 
     * @param Request $request
     * @return void
     */
    function updateShipmentStatus($data)
    {
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
        $shipmentId = ShipmentModel::where('order_code', $data['orderCode'])->first()?->shipment_id;
        if (!$shipmentId) return response('integration error: no order with this id in shipments table ', 500);
        $input = null;
        match ($data['orderStatus']) {
            WebHookStatusCode::DELIVERED  => $input = new UpdateStatus(
                id: $shipmentId,
                notes: $notes,
                deliveredField: new DeliveredField(deliveryType: new FullDeliveredType()),
            ),
            WebHookStatusCode::CANCELED => $input = new UpdateStatus( // TODO  dex 
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

        return (new ShipmentServices())->updateShipmentStatus($input, $output);
    }
}
