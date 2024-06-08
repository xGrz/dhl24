<?php

namespace xGrz\Dhl24\Actions;


use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLCourierBooking;

class BookCourierCancel extends ApiCalls
{
    protected string $method = 'cancelCourierBooking';
    protected array $payload = [
        'orders' => []
    ];

    /**
     * @throws DHL24Exception
     */
    public function delete(DHLCourierBooking|string $booking): bool
    {
        self::getBookingOrderId($booking);

        $result = $this->call()?->cancelCourierBookingResult?->item;
        $result->result
            ? $booking->delete()
            : throw new DHL24Exception($result->error);

        return true;
    }

    private function getBookingOrderId(DHLCourierBooking|string $booking): void
    {
        $this->payload['orders'][] = $booking instanceof DHLCourierBooking
            ? $booking->order_id
            : $booking;

    }
}
