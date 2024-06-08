<?php

namespace xGrz\Dhl24\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use xGrz\Dhl24\Actions\BookCourierCancel;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLCourierBooking;

class DHLBookingService
{
    private ?DHLCourierBooking $booking = null;

    public function __construct(DHLCourierBooking|int|null $booking)
    {
        if ($booking) $this->booking = self::loadBooking($booking);
    }

    public function query(): Builder
    {
        return DHLCourierBooking::query();
    }


    /**
     * @throws DHL24Exception
     */
    public function book(Carbon $from, Carbon $to, string $info = null): static
    {
        if (!$from->isSameDay($to)) throw new DHL24Exception('Pickup from and pickup to has different dates.', 1001);
        if ($from > $to) throw new DHL24Exception('Pickup from must be later than pickup to date.', 1002);
        if ($from->addHours(2) > $to) throw new DHL24Exception('Pickup from must be later than pickup to date.', 1003);

        self::storeCreateBooking($from, $to, $info);
        return $this;
    }

    public function cancel(): bool
    {
        return (new BookCourierCancel())->delete($this->booking);
    }

    public function bookingHours()
    {

    }


    private function loadBooking(DHLCourierBooking|int $booking): DHLCourierBooking
    {
        return $booking instanceof DHLCourierBooking
            ? $booking
            : DHLCourierBooking::find($booking);
    }

    private function storeCreateBooking(Carbon $from, Carbon $to, string $info = null): void
    {
        DHLCourierBooking::create([
            'pickup_from' => $from,
            'pickup_to' => $to,
            'additional_info' => $info
        ]);
    }
}
