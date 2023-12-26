<?php

namespace App\Http\Controllers\Core;

use Accurate\Shipping\Client\Client;
use Accurate\Shipping\Enums\Fields\CancellationReasonField;
use Accurate\Shipping\Enums\Fields\Core\DropDownField;
use Accurate\Shipping\Enums\Fields\ServiceField;
use Accurate\Shipping\Enums\Fields\ShipmentField;
use Accurate\Shipping\Enums\Fields\SizeField;
use Accurate\Shipping\Enums\Fields\ZoneField;
use Accurate\Shipping\Enums\Types\BooleanField;
use Accurate\Shipping\Enums\Types\PaymentField;
use Accurate\Shipping\Enums\Types\PriceField;
use Accurate\Shipping\Enums\Types\TypeField;
use Accurate\Shipping\Enums\Types\WebHookTypeCode;
use Accurate\Shipping\Models\Filters\ListShipmentFilter;
use Accurate\Shipping\Models\Inputs\Shipment as InputsShipment;
use Accurate\Shipping\Models\Inputs\Size;
use App\Http\Controllers\Controller;
use Accurate\Shipping\Services\Shipment as ServicesShipment;
use Exception;
use Illuminate\Http\Request;

use function PHPUnit\Framework\throwException;

class WebhookController extends Controller
{
    public function processRequest(Request $request, $integrationCode = null)
    {
        $data = $request->all();

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

        try {
            if (is_null($integrationCode)) {
                // make here the request of creating an integration.
            } else {
                // make here the functionality of sync shipments.
                // $integration = Integration::whereCode($integrationCode)->get();

                // eagle delivery agent (egyexpress)
                Client::init(
                    'http://localhost:8001/graphql',    #$integration->whereIsAgent()->get()->url
                    ['Authorization' =>                 #$integration->whereIsAgent()->get()->token
                    "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjMxOjgwMDIvZ3JhcGhxbCIsImlhdCI6MTcwMTU5OTczMCwiZXhwIjoxNzEwMjM5NzMwLCJuYmYiOjE3MDE1OTk3MzAsImp0aSI6InF6dmU2MTJBTzhQTUtNZ00iLCJzdWIiOiIyOSIsInBydiI6ImNjYzkzY2JiY2U3ZTExNTI2ZTc2ZjYyYTFkYTgxNTExMTYzMTY1MmYiLCJsb2dpbl9pZCI6OTQyfQ.tgEnvBolvubxfCDDYn_RZtHx7NIUFTukKozZLFKoYVs"]
                );
                // if ($data['shipmentId']) $shipments = $this->getShipments($data, $output);
                $shipments = $this->getShipments($data, $output);

                // egyexpress customer (eagle)
                Client::init(
                    'http://localhost:8001/graphql',    #$integration->whereIsClient()->get()->url
                    ['Authorization' =>                 #$integration->whereIsClient()->get()->token
                    "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjMxOjgwMDIvZ3JhcGhxbCIsImlhdCI6MTcwMTYxMTkwNywiZXhwIjoxNzEwMjUxOTA3LCJuYmYiOjE3MDE2MTE5MDcsImp0aSI6Im5RbW8wbjN2N0cwVUlVeE4iLCJzdWIiOiIzMiIsInBydiI6ImNjYzkzY2JiY2U3ZTExNTI2ZTc2ZjYyYTFkYTgxNTExMTYzMTY1MmYiLCJsb2dpbl9pZCI6OTQzfQ.q7S8UIAUvYHtB3u4M_cf-m9u2f7Wo4W4wm3gCQ7RO8E"]
                );

                if (!is_null($shipments))
                    // match ($data['topic']) {
                    return match (WebHookTypeCode::SHP_CREATE) {
                        WebHookTypeCode::SHP_CREATE         => $this->saveShipment($shipments, output: $output),
                        WebHookTypeCode::SHP_UPDATE         => $this->saveShipment($shipments, output: $output),
                        WebHookTypeCode::SHP_STATUS_UPDATE  => $this->updateShipmentStstus($shipments, output: $output),
                        WebHookTypeCode::SHP_DELETE         => $this->deleteShipment($shipments, output: $output),
                    };
            }
        } catch (Exception $e) {
            info("Error: " . $e->getMessage());
            throwException($e);
        }
    }

    function saveShipment($shipments = null, $output)
    {
        $outputShipments = [];

        foreach ($shipments as $key => $shipment) {
            /** @var mixed */
            $response = (new ServicesShipment)->saveShipment(input: new InputsShipment(
                weight: $shipment['weight'],
                price: $shipment['price'],
                recipientPhone: $shipment['recipientPhone'],
                recipientMobile: $shipment['recipientMobile'],
                recipientAddress: $shipment['recipientAddress'],
                recipientSubzoneId: $shipment['recipientSubzone']['id'],
                recipientZoneId: $shipment['recipientZone']['id'],
                recipientName: $shipment['recipientName'] ?? 'dd',
                senderName: $shipment['senderName'],
                senderPhone: $shipment['senderPhone'],
                senderMobile: $shipment['senderMobile'],
                senderAddress: $shipment['senderAddress'],
                senderSubzoneId: $shipment['senderSubzone']['id'],
                senderZoneId: $shipment['senderZone']['id'],
                id: null,
                code: null,
                piecesCount: 1,
                serviceId: 1,
                notes: $shipment['notes'] ?? "",
                description: $shipment['description'] ?? "",
                refNumber: $shipment['refNumber'] ?? "",
                priceTypeCode: PriceField::tryFrom($shipment['priceType']['code']) ?? PriceField::INCLD,
                paymentTypeCode: PaymentField::tryFrom($shipment['paymentType']['code']) ?? PaymentField::COLC,
                openableCode: BooleanField::tryFrom($shipment['openable']['code']) ?? BooleanField::Y,
                typeCode: TypeField::tryFrom($shipment['type']['code']) ?? TypeField::FDP,
                size: new Size($shipment['size']['length'], $shipment['size']['width'], $shipment['size']['height']),
            ), output: $output);

            $shipment = $response->original['data'];
            array_push($outputShipments, $shipment);
        }

        return json_encode($outputShipments);
    }

    function updateShipmentStstus($shipments = null, $output)
    {
    }

    function deleteShipment($shipments = null, $output)
    {
    }

    private static function getShipments($data, $output)
    {
        if (is_null(@$data['shipmentId']))
            $response = self::listShipments(output: $output);

        $shipments = $response['data'];
        $paginatorInfo = $response['paginatorInfo'];

        if ($paginatorInfo['hasMorePages']) {
            $response = self::listShipments(page: $paginatorInfo['currentPage'] + 1, output: $output);
            $shipments = array_merge($shipments, $response['data']);
            $paginatorInfo = $response['paginatorInfo'];
        }
        return $shipments;
    }

    private static function listShipments($first = 100, $page = 1, $output = [])
    {
        /** @var mixed */
        $response = (new ServicesShipment)->listShipments(input: new ListShipmentFilter(id: [1464, 1316]), output: $output, first: $first, page: $page);
        return $response->original['data']['listShipments'];
    }
}
