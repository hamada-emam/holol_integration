<?php

namespace App\Http\Controllers\Accurate\DeliveryAgent;

use Illuminate\Http\Request;
use Accurate\Shipping\Enums\Fields\CancellationReasonField;
use Accurate\Shipping\Enums\Fields\Core\DropDownField;
use Accurate\Shipping\Enums\Fields\ServiceField;
use Accurate\Shipping\Enums\Fields\ShipmentField;
use Accurate\Shipping\Enums\Fields\SizeField;
use Accurate\Shipping\Enums\Fields\ZoneField;
use Accurate\Shipping\Enums\Types\IntegrationTopicField;
use Accurate\Shipping\Models\Filters\ListShipmentFilter;
use Accurate\Shipping\Models\Filters\ShipmentById;
use Accurate\Shipping\Services\Shipment as ShipmentService;
use App\Models\Shipment as ShipmentModel;
use App\Http\Controllers\Controller;
use App\Jobs\CancelOrder;
use App\Jobs\SyncOrder;
use App\Traits\InitAccurate;

class Shipment extends Controller
{
    use InitAccurate;

    // will seperate the imelementation for every client to be use a logic deffrent from others 
    private $shipmentId;
    private array $output;
    /**
     * Create a new OrderService instance.
     * @var ShipmentService $shipment
     */
    public function __construct(Request $request, public ShipmentService $shipment)
    {
        $this->initClient($request->route('id'));

        $this->output =
            [
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
    }

    /**
     * Handle imile WebHook 
     *
     * @param Request $request
     * @return void
     */
    function callback(Request $request)
    {
        // -------------holol imile delivery agent----------
        info("from accurate");
        // -----------------
        $data = $request->all();
        $this->shipmentId = $data['shipmentId'];
        info("from accurate  web hook call back for the accurate webhook");
        info($data);
        info("payload shipment ids at integration");
        info($this->shipmentId);

        try {
            match ($data['topic']) {
                (IntegrationTopicField::SHP_CREATE)->value  => $this->create(),
                (IntegrationTopicField::SHP_UPDATE)->value => $this->update(),
                (IntegrationTopicField::SHP_DELETE)->value => $this->delete(),
                (IntegrationTopicField::SHP_STATUS_UPDATE)->value => $this->updateStatus(),
            };
            return response("success", 200);
        } catch (\Exception $e) {
            info($e);
            return response('error', 500);
        }
    }

    // Actions
    private function create()
    {
        $response = $this->listShipments();
        $paginatorInfo = $response['paginatorInfo'];
        $shipments = $response['data'];

        if ($paginatorInfo['hasMorePages']) {
            $response = self::listShipments(page: $paginatorInfo['currentPage'] + 1);
            $shipments = array_merge($shipments, $response['data']);
            $paginatorInfo = $response['paginatorInfo'];
        }

        foreach ($shipments as $shipment) {
            $syncedShipment = ShipmentModel::where('shipment_id', $shipment['id'])->first();
            if (!$syncedShipment) {
                $syncedShipment = new ShipmentModel();
                $syncedShipment->forceFill(['shipment_id' =>  $shipment['id'], 'shipment' => serialize($shipment)]);
                $syncedShipment->save();
                info("create webhook from accurate callback webhook");
                info($syncedShipment);
                info("============\n");
                SyncOrder::dispatch($shipment);
            } else if ($syncedShipment) {
                info("update webhook ");
                info($syncedShipment);
                $this->update($shipment['id']);
            }
        }
    }

    private function update($id = null)
    {
        /** @var mixed */
        $response = $this->shipment->shipment(new ShipmentById(id: $id ?? $this->shipmentId), output: $this->output);
        $shipment = $response->getData()->data->shipment;
        $syncedShipment = ShipmentModel::where('shipment_id', $this->shipmentId)->first();
        if ($syncedShipment) {
            $syncedShipment->shipment = serialize($shipment);
            $syncedShipment->save();
            info("update shipment webhook from accurate webhook callback");
            info($syncedShipment);
            info("============\n");
            SyncOrder::dispatch($shipment);
        }
    }

    private function updateStatus()
    {
        info("from updat status web hook for accurate callback");
        $syncedShipment = ShipmentModel::where('shipment_id', $this->shipmentId)->first();
        $syncedShipment->delete();
    }

    /**
     * handle delete last action on the shipment
     *
     * @return void
     */
    private function delete()
    {
        info("delete Function start from accurate call back webhook");
        $syncedShipment = ShipmentModel::where('shipment_id', $this->shipmentId)->first();

        if ($syncedShipment) {
            $syncedShipment->delete();
            info("from delete accurate  web hook call back");
            info($syncedShipment);
            CancelOrder::dispatch($syncedShipment->order_code)->onQueue('high'); # it will be cancle shipment
        }
    }

    // helper functions 
    private function listShipments($first = 100, $page = 1)
    {
        /** @var mixed */
        $response = $this->shipment->listShipments(new ListShipmentFilter(id: is_array($this->shipmentId) ? $this->shipmentId : [$this->shipmentId]), output: $this->output, first: $first, page: $page);
        return $response->original['data']['listShipments'];
    }
}
