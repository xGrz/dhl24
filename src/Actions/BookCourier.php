<?php

namespace xGrz\Dhl24\Actions;


use Illuminate\Support\Carbon;
use xGrz\Dhl24\Models\DHLCourierBooking;
use xGrz\Dhl24\Models\DHLShipment;

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

    public function book(array|int $shipmentIdList, Carbon $from, Carbon $to, ?string $additionalInfo = null): string
    {
        $shipmentIdList = collect($shipmentIdList)->toArray();
        $this->payload['shipmentIdList'] = $shipmentIdList;
        $this->payload['pickupDate'] = $from->format('Y-m-d');
        $this->payload['pickupTimeFrom'] = $from->format('H:i');
        $this->payload['pickupTimeTo'] = $to->format('H:i');
        $this->payload['additionalInfo'] = $additionalInfo;

        $bookId = $this->call()?->bookCourierResult?->item;
        if ($bookId) {
            $courierBook = DHLCourierBooking::create([
                'pickup_from' => $from->setSeconds(0),
                'pickup_to' => $to->setSeconds(0),
                'additional_info' => $additionalInfo,
                'order_id' => $bookId
            ]);

            $updated = DHLShipment::whereIn('number', $shipmentIdList)
                ->update(['courier_booking_id' => $courierBook->id]);

        }
        return $bookId;
    }
}
