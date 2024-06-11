<?php

namespace xGrz\Dhl24\Actions;


use xGrz\Dhl24\Exceptions\DHL24Exception;
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

    /**
     * @throws DHL24Exception
     */
    public function book(array|int $shipmentIdList, DHLCourierBooking $booking): string
    {
        $shipmentIdList = collect($shipmentIdList)->toArray();
        $this->payload['shipmentIdList'] = $shipmentIdList;
        self::buildPayloadFromBooking($booking);

        $bookId = $this->call()?->bookCourierResult?->item;
        if ($bookId) {
            $booking
                ->fill([
                    'order_id' => $bookId
                ])
                ->save();

            DHLShipment::query()
                ->whereIn('number', $shipmentIdList)
                ->update(['courier_booking_id' => $booking->id]);

        }
        return $bookId;
    }

    private function buildPayloadFromBooking(DHLCourierBooking $booking): void
    {
        $this->payload['pickupDate'] = $booking->pickup_from->format('Y-m-d');
        $this->payload['pickupTimeFrom'] = $booking->pickup_from->format('H:i');
        $this->payload['pickupTimeTo'] = $booking->pickup_to->format('H:i');
        $this->payload['additionalInfo'] = $booking->additional_info;

    }
}
