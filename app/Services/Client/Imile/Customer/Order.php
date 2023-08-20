<?php

namespace App\Services\Client\Imile\Customer;

use Illuminate\Support\Facades\Http;
use App\Models\Shipment as ShipmentModel;
use App\Models\Zone;

class Order
{
    /**
     * @param object $shipment
     * @param string $token
     * @return void
     */
    static function createOrders($shipment, $token)
    {
        $body =
            [
                // "customerId" => "C2103720301",
                "customerId" => "C21018520", # dev
                "accessToken" => $token,
                // "Sign" => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU",
                "Sign" => "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAN2lOq+RJdIifbPL", # dev
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
                    "consignorCity" => "stevecity2", //self::getCity($shipment['senderZone['id)'],
                    "consignorArea" => "stevecity2area1", //self::getArea($shipment['senderSubzone['id)'],
                    "consignorZipCode" => @$shipment['senderPostalCode'] ?? "",
                    "consigneeContact" => $shipment['recipientName'] ?? "default",
                    "consigneeMobile" => $shipment['recipientMobile'],
                    "consigneePhone" => $shipment['recipientPhone'],
                    "consigneeCity" => "HT998test", //self::getCity($shipment['recipientZone['id']),
                    "consigneeArea" => "HTtest9999992222", //self::getArea($shipment['recipientSubzone['id']),
                    "consigneeZipCode" => @$shipment['recipientPostalCode'] ?? "",
                    "consigneeAddress" => @$shipment['recipientAddress'] ?? "",
                    "consigneeLongitude" => @$shipment['recipientLongitude'] ?? "",
                    "consigneeLatitude" => @$shipment['recipientLatitude'] ?? "",
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
                    "skuName" => "Default"
                ]
            ];

        // $response =  Http::post('https://openapi.imile.com/client/order/createB2cOrder', $body);
        $response = Http::post('https://openapi.52imile.cn/client/order/createB2cOrder', $body); #dev
        info($response);
        info(json_encode($response->json()));
        if ($response->json()['code'] != "200") throw new \Exception($response->json());
        info("response for creating order\n");
        info($response);
        /** @var ShipmentModel */
        $localShipment = ShipmentModel::where('shipment_id', $shipment['id'])->first();
        $localShipment->updateWithOrders($shipment['code']);
    }

    /**
     * @param object $shipment
     * @param string $token
     * @return void
     */
    static function updateOrders($shipment, $token)
    {
        $localShipment = ShipmentModel::where('shipment_id', $shipment['id'])->first();
        $body =
            [
                // "customerId" => "C2103720301",
                "customerId" => "C21018520", # dev
                "accessToken" => $token,
                // "Sign" => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU",
                "Sign" => "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAN2lOq+RJdIifbPL", # dev
                "signMethod" => "SimpleKey",
                "format" => "json",
                "version" => "1.0.0",
                "timestamp" => 1648534306945,
                "timeZone" => "+4",
                "param" => [
                    "orderCode" => $localShipment->order_code,
                    "orderType" => self::getOrderTypeCode(),
                    "consignorContact" => $shipment['senderName'],
                    "consignorPhone" => $shipment['senderPhone'],
                    "consignorMobile" => $shipment['senderMobile'],
                    "consignorAddress" => $shipment['senderAddress'],
                    "consignorCity" => "stevecity2", //self::getCity($shipment['senderZone['id)'],
                    "consignorArea" => "stevecity2area1", //self::getArea($shipment['senderSubzone['id)'],
                    "consignorZipCode" => @$shipment['senderPostalCode'] ?? "",
                    "consigneeContact" => $shipment['recipientName'],
                    "consigneeMobile" => $shipment['recipientMobile'],
                    "consigneePhone" => $shipment['recipientPhone'],
                    "consigneeCity" => "HT998test", //self::getCity($shipment['recipientZone['id']),
                    "consigneeArea" => "HTtest9999992222", //self::getArea($shipment['recipientSubzone['id']),
                    "consigneeZipCode" => @$shipment['recipientPostalCode'] ?? "",
                    "consigneeAddress" => @$shipment['recipientAddress'] ?? "",
                    "consigneeLongitude" => @$shipment['recipientLongitude'] ?? "",
                    "consigneeLatitude" => @$shipment['recipientLatitude'] ?? "",
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
                    "skuName" => "Default"
                ]
            ];

        // $response =  Http::post('https://openapi.imile.com/client/order/modifyOrder', $body);
        $response = Http::post('https://openapi.52imile.cn/client/order/modifyOrder', $body); #dev
        info("response for updating order\n");
        info($response);
        if ($response->json()['code'] != "200") throw new \Exception($response->json());
    }

    /**
     * @param object $shipment
     * @param string $token
     * @return void
     */
    static function delete($orderCode, $token)
    {
        $body =
            [
                // "customerId" => "C2103720301",
                "customerId" => "C21018520", # dev
                "accessToken" => $token,
                // "Sign" => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU",
                "Sign" => "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAN2lOq+RJdIifbPL", # dev
                "signMethod" => "SimpleKey",
                "format" => "json",
                "version" => "1.0.0",
                "timestamp" => 1648534306945,
                "timeZone" => "+4",
                "param" => [
                    "orderCode" => $orderCode
                    // "waybillNo" => "6062022280678"
                ]
            ];

        // $response =  Http::post('https://openapi.imile.com/client/order/deleteOrder', $body);
        $response = Http::post('https://openapi.52imile.cn/client/order/deleteOrder', $body); #dev
        if ($response->json()['code'] != "200") throw new \Exception($response->json());
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
    private static function getOrderTypeCode(): string
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
    private static function getPaymentMethod($paymentType): string
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
    private static function getArea($subZoneId): string
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
    private static function getCity($zoneId): string
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
    private static function getCountry(): string
    {
        return "KSA";
    }
}
