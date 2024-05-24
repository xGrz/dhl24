<?php

namespace xGrz\Dhl24\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MyShipments extends ApiCalls
{
    protected string $method = 'GetMyShipments';
    protected array $payload = [
        'createdFrom' => '',
        'createdTo' => '',
        'offset' => 0
    ];


    public function get(Carbon $from = null, Carbon $to = null, int $page = 1): Collection
    {
        $this->payload['offset'] = (($page - 1) * 100);
        $this->payload['createdFrom'] = $from?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->payload['createdTo'] = $to?->format('Y-m-d') ?? now()->format('Y-m-d');
        $shipments = $this->call()?->getMyShipmentsResult;
        return isset($shipments->item) ? collect($shipments->item) : new Collection();
    }
}
