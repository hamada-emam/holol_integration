<?php

namespace App\Http\Controllers\Accurate\DeliveryAgent;

use Accurate\Shipping\Enums\Fields\BranchField;
use Accurate\Shipping\Enums\Fields\CancellationReasonField;
use Accurate\Shipping\Enums\Fields\Core\DropDownField;
use Accurate\Shipping\Enums\Fields\CustomerField;
use Accurate\Shipping\Enums\Fields\MessageField;
use Accurate\Shipping\Enums\Fields\ServiceField;
use Accurate\Shipping\Enums\Fields\ShipmentField;
use Accurate\Shipping\Enums\Fields\SizeField;
use Accurate\Shipping\Enums\Fields\ZoneField;
use Accurate\Shipping\Enums\Types\ShipmentStatusField;
use Accurate\Shipping\Models\Filters\ListShipmentFilter;
use Accurate\Shipping\Services\Shipment as ServicesShipment;
use App\Http\Controllers\Controller;
use App\Jobs\SyncShipment;
use App\Models\Shipment as ModelsShipment;
use Illuminate\Support\Facades\DB;

class Shipment extends Controller
{
    /**
     * you list OTD but action taken with delivery agent is DTR
     *
     * @return void
     */
    static function syncShipments()
    {
        $output = [
            ShipmentField::ID,
            ShipmentField::CODE,
            ShipmentField::DATE,
            ShipmentField::CREATEDAT,
            ShipmentField::UPDATEDAT,
            ShipmentField::DELIVERYDATE,
            ShipmentField::DELIVEREDCANCELEDDATE,
            ShipmentField::DESCRIPTION,
            ShipmentField::NOTES,
            ShipmentField::RECIPIENTLATITUDE,
            ShipmentField::PIECESCOUNT,
            ShipmentField::RETURNPIECESCOUNT,
            ShipmentField::WEIGHT,
            ShipmentField::ATTEMPTS,
            ShipmentField::AMOUNT,
            ShipmentField::DELIVERYFEES,
            ShipmentField::EXTRAWEIGHTFEES,
            ShipmentField::COLLECTIONFEES,
            ShipmentField::TOTALAMOUNT,
            ShipmentField::PRICE,
            ShipmentField::RETURNINGDUEFEES,
            ShipmentField::DELIVEREDAMOUNT,
            ShipmentField::RETURNEDVALUE,
            ShipmentField::ALLDUEFEES,
            ShipmentField::COLLECTEDFEES,
            ShipmentField::CUSTOMERDUE,
            ShipmentField::COLLECTEDAMOUNT,
            ShipmentField::PENDINGCOLLECTIONAMOUNT,
            ShipmentField::RETURNFEES,
            ShipmentField::RETURNDELIVERYFEES,
            ShipmentField::RETURNCOLLECTIONFEES,
            ShipmentField::POSTFEES,
            ShipmentField::TAX,
            ShipmentField::RECIPIENTNAME,
            ShipmentField::RECIPIENTPHONE,
            ShipmentField::RECIPIENTMOBILE,
            ShipmentField::RECIPIENTADDRESS,
            ShipmentField::RECIPIENTPOSTALCODE,
            ShipmentField::SENDERNAME,
            ShipmentField::SENDERPHONE,
            ShipmentField::SENDERMOBILE,
            ShipmentField::SENDERADDRESS,
            ShipmentField::SENDERPOSTALCODE,
            ShipmentField::SIGNATURE,
            ShipmentField::service([
                ServiceField::ID,
                ServiceField::CODE,
                ServiceField::NAME,
            ]),
            ShipmentField::senderZone([
                ZoneField::ID,
                ZoneField::CODE,
                ZoneField::NAME,
            ]),
            ShipmentField::senderSubZone([
                ZoneField::ID,
                ZoneField::CODE,
                ZoneField::NAME,
            ]),
            ShipmentField::recipientZone([
                ZoneField::ID,
                ZoneField::CODE,
                ZoneField::NAME,
            ]),
            ShipmentField::recipientSubzone([
                ZoneField::ID,
                ZoneField::CODE,
                ZoneField::NAME,
            ]),
            ShipmentField::cancellationReason([
                CancellationReasonField::ID,
                CancellationReasonField::CODE,
            ]),
            ShipmentField::size([
                SizeField::LENGTH,
                SizeField::WIDTH,
                SizeField::HEIGHT
            ]),
            ShipmentField::type([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::openable([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::paymentType([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::priceType([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::deliveryType([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::returnType([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::businessType([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
            ShipmentField::returnStatus([
                DropDownField::ID,
                DropDownField::CODE,
                DropDownField::NAME
            ]),
        ];

        $existsIds = [];
        /** @var mixed */
        $response = (new ServicesShipment)->listShipments(input: new ListShipmentFilter(statusCode: ShipmentStatusField::OTD), output: $output, first: 100);

        $shipments = $response->original['data']['listShipments']['data'];
        foreach ($shipments as $shipment) {
            $existsIds[] = $shipment['id'];
            /** @var ModelsShipment */
            $syncedShipment = ModelsShipment::where('shipment_id', $shipment['id'])->first();
            if (!$syncedShipment) {
                $syncedShipment = new ModelsShipment();
                $syncedShipment->forceFill([
                    'shipment_id' => $shipment['id'],
                    'shipment' => serialize($shipment),
                ]);
                $syncedShipment->save();
                SyncShipment::dispatch($shipment);
            }
        }

        DB::table('shipments')->whereNotIn('shipment_id', $existsIds)->delete();
    }
}
