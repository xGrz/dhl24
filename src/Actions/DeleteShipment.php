<?php

namespace xGrz\Dhl24\Actions;


use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class DeleteShipment extends ApiCalls
{
    protected string $method = 'deleteShipments';
    protected array $payload = [
        'shipments' => []
    ];


    /**
     * @throws DHL24Exception
     */
    public function delete(DHLShipment|int $shipment): true
    {
        $this->payload['shipments'][] = DHL24::getShipment($shipment)->number;

        $response = $this->call()?->deleteShipmentsResult?->item;
        if ($response->result) {
            self::removeShipment();
            return true;
        } else {
            throw new DHL24Exception($response->error);
        }
    }

    private function removeShipment(): void
    {
        $shipmentNumber = $this->payload['shipments'][0];
        DHLShipment::where('number', $shipmentNumber)->delete();
    }
}
