<?php

namespace App\Http\Controllers\Provider\Imile\Customer;

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
use Illuminate\Support\Facades\Http;
use App\Models\Shipment;
use App\Models\Zone;
use Exception;
use Illuminate\Http\Request;

class Order extends Controller
{
    /**
     * 
     *
     *
     *  -- dev --
     * "customerId" => "C21018520", # TODO get it from global place 
     * "Sign" => "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAN2lOq+RJdIifbPL", # TODO get it from global place 
     * $response = Http::post('https://openapi.52imile.cn/auth/accessToken/grant', $body); #dev
     * 
     * @param array $shipment
     * @param string $token
     * @return void
     */
    static function syncOrders($shipment, $token)
    {
        $body =
            [
                "customerId" => "C2103720301",
                "accessToken" => $token,
                "Sign" => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU",
                "signMethod" => "SimpleKey",
                "format" => "json",
                "version" => "1.0.0",
                "timestamp" => 1648534306945,
                "timeZone" => "+4",
                "param" => [
                    "orderCode" => $shipment['code'],
                    "orderType" => self::getOrderTypeCode(),
                    "consignorContact" => $shipment['senderName'],
                    "consignorPhone" => $shipment['senderPhone'],
                    "consignorMobile" => $shipment['senderMobile'],
                    "consignorAddress" => $shipment['senderAddress'],
                    "consignorCity" => self::getCity($shipment['senderZone']['id']),
                    "consignorArea" => self::getArea($shipment['senderSubzone']['id']),
                    "consignorZipCode" => $shipment['senderPostalCode'],
                    "consigneeContact" => $shipment['recipientName'],
                    "consigneeMobile" => $shipment['recipientMobile'],
                    "consigneePhone" => $shipment['recipientPhone'],
                    "consigneeCity" => self::getCity($shipment['recipientZone']['id']),
                    "consigneeArea" => self::getArea($shipment['recipientSubzone']['id']),
                    "consigneeZipCode" => $shipment['recipientPostalCode'],
                    "consigneeAddress" => $shipment['recipientAddress'],
                    "consigneeLongitude" => @$shipment['recipientLongitude'],
                    "consigneeLatitude" => @$shipment['recipientLatitude'],
                    "totalCount" => $shipment['piecesCount'],
                    "totalWeight" => $shipment['weight'],
                    "orderDescription" => $shipment['description'],
                    "isSupportUnpack" => $shipment['openable']['code'] == "Y" ? 1 : 0,
                    "totalVolume" => $shipment['size']['length'] * $shipment['size']['width'] * $shipment['size']['height'],
                    "consignorCountry" => "KSA",
                    "consigneeCountry" => "KSA",
                    "consigneeEmail" => "CustomersEmail@gmail.com",
                    "collectingMoney" => $shipment['totalAmount'],
                    "paymentMethod" => self::getPaymentMethod($shipment['paymentType']['code']),
                    "skuTotal" => 1,
                    "skuName" => "Default",
                ]
            ];

        $response =  Http::post('https://openapi.imile.com/client/order/createB2cOrder', $body);
        if ($response->json()['code'] != "200") throw new \Exception($response->json()['message']);

        // update shipment with order
        $localShipment = Shipment::where('shipment_id', $shipment['id'])->first();
        $localShipment->updateWithOrders($shipment['code']);
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
    function callback(Request $request)
    {
        $data = $request->all();

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
