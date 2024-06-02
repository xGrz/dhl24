<?php

namespace xGrz\Dhl24\Services;


use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use xGrz\Dhl24\Actions\MyShipments;
use xGrz\Dhl24\Actions\ShipmentDataFromServer;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;

class DHLApiImportService
{

    private Carbon $from;
    private Carbon $to;

    private Collection $shipments;

    public function __construct(Carbon $from = null, Carbon $to = null)
    {
        $this->shipments = new Collection();
        $this->from = $from ?? now();
        $this->to = $to ?? now();
        self::importMyShipments();
        self::storeShipments();
    }

    private function importMyShipments(int $page = 1): void
    {
        $shipments = (new MyShipments())->get($this->from, $this->to, $page);
        if ($shipments->count() === 0) return;

        $shipmentsList = [];
        $shipments->each(function ($shipment) use (&$shipmentsList) {
            $shipmentsList[] = $shipment->shipmentId;
        });
        (new ShipmentDataFromServer)
            ->get($shipmentsList)
            ->each(function ($shipment) {
                $this->shipments->push($shipment);
            });

        if ($shipments->count() < 100) return;
        self::importMyShipments(++$page);
    }

    private function storeShipments(): void
    {
        $this
            ->shipments
            ->each(function ($shipment) {
                $exists = (bool)DHLShipment::where('number', $shipment->shipmentId)->count();
                if (!$exists) self::storeShipmentLocally($shipment);
            });
    }

    private function storeShipmentLocally(object $shipment): void
    {
        //dd($shipment);
        $wizard= DHL24::wizard()
            ->shipmentDate(Carbon::parse($shipment->shipmentTime->shipmentDate))
            ->content($shipment->content)
            ->comment($shipment->comment)
            ->reference($shipment->reference)
            ->costCenter(isset($shipment->costsCenter) ? DHLCostCenter::where('name', $shipment->costsCenter)->first() : null)
            ->shipperName($shipment->shipper->name)
            ->shipperPostalCode($shipment->shipper->postalCode)
            ->shipperCity($shipment->shipper->city)
            ->shipperStreet($shipment->shipper->street)
            ->shipperHouseNumber($shipment->shipper->houseNumber)
            ->shipperContactPerson($shipment->shipper->contactPerson)
            ->shipperContactEmail($shipment->shipper->contactEmail)
            ->shipperContactPhone($shipment->shipper->contactPhone)
            ->receiverName($shipment->receiver->name)
            ->receiverPostalCode($shipment->receiver->postalCode, $shipment->receiver->country)
            ->receiverCity($shipment->receiver->city)
            ->receiverStreet($shipment->receiver->street)
            ->receiverHouseNumber($shipment->receiver->houseNumber)
            ->receiverContactPerson($shipment->receiver->contactPerson)
            ->receiverContactEmail($shipment->receiver->contactEmail)
            ->receiverContactPhone($shipment->receiver->contactPhone)
            ->shipmentType(DHLDomesticShipmentType::from($shipment->service->product))
            ->collectOnDelivery($shipment->service->collectOnDeliveryValue, $shipment->service->collectOnDeliveryReference)
            ->shipmentValue($shipment->service->insuranceValue);

        if (is_array($shipment->pieceList->item)) {
            foreach ($shipment->pieceList->item as $shipmentItem) {
                self::addItem($shipmentItem, $wizard);
            }
        } else {
            self::addItem($shipment->pieceList->item, $wizard);
        }

        $dhlShipment = $wizard->store();
        $dhlShipment->update(['number' => $shipment->shipmentId]);


    }

    private function addItem($shipmentItem, DHLShipmentWizard $wizard): DHLShipmentWizard
    {
        $wizard->addItem(
            DHLShipmentItemType::findByName($shipmentItem->type),
            $shipmentItem->quantity,
            $shipmentItem->weight,
            $shipmentItem->width,
            $shipmentItem->height,
            $shipmentItem->length,
            $shipmentItem->nonStandard,
        );
        return $wizard;
    }

}
