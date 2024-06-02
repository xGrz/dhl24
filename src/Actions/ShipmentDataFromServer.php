<?php

namespace xGrz\Dhl24\Actions;

use Illuminate\Support\Collection;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class ShipmentDataFromServer extends ApiCalls
{
    protected string $method = 'GetLabelsData';
    protected array $payload = [
        'itemsToLabelData' => [],
    ];

    private Collection $items;

    /**
     * @throws DHL24Exception
     */
    public function get(string|int|array $shipments): Collection
    {
        $shipments = collect($shipments);
        $shipments->each(function ($shipmentNumber) {
            $this->payload['itemsToLabelData'][]['shipmentId'] = $shipmentNumber;
        });

        $this->items = collect($this->call()?->getLabelsDataResult?->item);
        return $this->items;
    }
}
