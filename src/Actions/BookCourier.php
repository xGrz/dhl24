<?php

namespace xGrz\Dhl24\Actions;



use Illuminate\Support\Carbon;

class BookCourier extends ApiCalls
{
    protected string $method = 'bookCourier';
    protected array $payload = [
        'pickupDate' => null,
        'pickupTimeFrom' => null,
        'pickupTimeTo' => null,
        'additionalInfo' => null,
        'shipmentIdList' => []
    ];

    public function book(array|int $shipmentIdList, Carbon $from, Carbon $to, ?string $additionalInfo = null)
    {
        $this->payload['shipmentIdList'] = collect($shipmentIdList)->toArray();
        $this->payload['pickupDate'] = $from->format('Y-m-d');
        $this->payload['pickupTimeFrom'] = $from->format('H:i');
        $this->payload['pickupTimeTo'] = $to->format('H:i');
        $this->payload['additionalInfo'] = $additionalInfo;
        dd($this->call());
    }
}
