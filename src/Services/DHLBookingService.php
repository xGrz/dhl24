<?php

namespace xGrz\Dhl24\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use xGrz\Dhl24\Actions\BookCourier;
use xGrz\Dhl24\Actions\BookCourierCancel;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLCourierBooking;
use xGrz\Dhl24\Models\DHLShipment;

class DHLBookingService
{

    public function query(): Builder
    {
        return DHLCourierBooking::query();
    }

    /**
     * @throws DHL24Exception
     */
    public function book(Carbon $from, Carbon $to, DHLShipment|Collection $shipments, string $info = null): static
    {
        if (!$from->isSameDay($to)) throw new DHL24Exception('Pickup [from] and pickup [to] has different dates.', 1001);
        if ($from > $to) throw new DHL24Exception('Pickup [from] must be later than pickup [to] date.', 1002);
        if ($from->copy()->addHours(2) > $to) throw new DHL24Exception('Pickup [from] must be later than pickup [to] date.', 1003);

        if ($shipments instanceof DHLShipment) {
            $shipments = (new Collection([$shipments]));
        }


        $booking = self::prepareBooking($from, $to, $info);
        self::processDhlBooking($booking, $shipments);
        return $this;
    }

    public function cancel(DHLCourierBooking $booking): bool
    {
        return (new BookCourierCancel())->delete($booking);
    }

    /**
     * @throws DHL24Exception
     */
    public function options(string $postalCode): DHLPostalCodeService
    {
        return new DHLPostalCodeService($postalCode);
    }

    private function prepareBooking(Carbon $from, Carbon $to, string $info = null): DHLCourierBooking
    {
        return (new DHLCourierBooking())->fill([
            'pickup_from' => $from->setSeconds(0),
            'pickup_to' => $to->setSeconds(0),
            'additional_info' => $info
        ]);
    }

    /**
     * @throws DHL24Exception
     */
    private function processDhlBooking(DHLCourierBooking $booking, Collection $shipments): void
    {
        $shipmentList = $shipments->map(fn($shipment) => $shipment->number)->toArray();
        (new BookCourier())->book($shipmentList, $booking);
    }




}
