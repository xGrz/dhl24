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
    private function processDhlBooking(DHLCourierBooking $booking, Collection $shipments): string
    {
        $shipmentList = $shipments->map(fn($shipment) => $shipment->number)->toArray();
        return (new BookCourier())->book($shipmentList, $booking);
    }

    public function cancel(DHLCourierBooking $booking): bool
    {
        return (new BookCourierCancel())->delete($booking);
    }

//    private ?DHLCourierBooking $booking = null;
//    private ?DHLShipment $shipment = null;
//
//    public function __construct(DHLCourierBooking|int|null $booking = null, ?DHLShipment $shipment = null)
//    {
//        if ($booking) $this->booking = self::loadBooking($booking);
//        if ($shipment) $this->shipment = $shipment;
//    }
//
//
//    /**
//     * @throws DHL24Exception
//     */
//    public function book(Carbon $from, Carbon $to, string $info = null): static
//    {
//        if (!$from->isSameDay($to)) throw new DHL24Exception('Pickup from and pickup to has different dates.', 1001);
//        if ($from > $to) throw new DHL24Exception('Pickup from must be later than pickup to date.', 1002);
//        if ($from->addHours(2) > $to) throw new DHL24Exception('Pickup from must be later than pickup to date.', 1003);
//
//        self::storeCreateBooking($from, $to, $info);
//        return $this;
//    }
//
//    public function cancel(): bool
//    {
//        return (new BookCourierCancel())->delete($this->booking);
//    }
//
//
//    /**
//     * @throws DHL24Exception
//     */
//    public function getBookingOptions(string $postalCode = null, Carbon $pickupDate = null, int $maxDays = 5): array
//    {
//        $postalCode = $postalCode ? self::formatPostalCode($postalCode) : $this->shipment->shipper_postal_code;
//        if (!$postalCode) throw new DHL24Exception('Pickup postal code is required.', 1010);
//        if (!$pickupDate) $pickupDate = $this->shipment?->shipment_date ?? now();
//
//        $bookings = [];
//        for($i=0; $i<$maxDays; $i++) {
//            $bookings[$pickupDate->format('Y-m-d')] = self::bookingHours($postalCode, $pickupDate);
//            $pickupDate->addDay();
//        }
//        return $bookings;
//    }
//
//    /**
//     * @throws DHL24Exception
//     */
//    public function bookingHours(string $postalCode = null, Carbon $pickupDate = null)
//    {
//        $postalCode = $postalCode ? self::formatPostalCode($postalCode) : $this->shipment->shipper_postal_code;
//        if (!$postalCode) throw new DHL24Exception('Pickup postal code is required.', 1010);
//        if (!$pickupDate) $pickupDate = $this->shipment?->shipment_date ?? now();
//
//        $postalCodeServices = (new PostalCodeServices())->get($postalCode, $pickupDate);
//        return [
//            'from' => self::getBookingStart($postalCodeServices->exPickupStart(), $postalCodeServices->exPickupEnd()),
//            'to' => self::getBookingEnd($postalCodeServices->exPickupStart(), $postalCodeServices->exPickupEnd())
//        ];
//    }
//
//
//    private function loadBooking(DHLCourierBooking|int $booking): DHLCourierBooking
//    {
//        return $booking instanceof DHLCourierBooking
//            ? $booking
//            : DHLCourierBooking::find($booking);
//    }
//
//    private function storeCreateBooking(Carbon $from, Carbon $to, string $info = null): void
//    {
//        DHLCourierBooking::create([
//            'pickup_from' => $from,
//            'pickup_to' => $to,
//            'additional_info' => $info
//        ]);
//    }
//
//    private function getBookingStart(string $from, string $to): array
//    {
//        $from = Carbon::parse($from)->ceilMinutes(15);
//        $to = Carbon::parse($to)->subHours(2);
//        $hours = CarbonPeriod::create($from, '15 minutes', $to)->toArray();
//
//        $startHours = [];
//        foreach ($hours as $hour) {
//            $startHours[] = $hour->format('H:i');
//        }
//        return $startHours;
//    }
//
//    private function getBookingEnd(string $from, string $to): array
//    {
//        $from = Carbon::parse($from)->ceilMinutes(15)->addHours(2);
//        $to = Carbon::parse($to);
//        $hours = CarbonPeriod::create($from, '15 minutes', $to)->toArray();
//
//        $endHours = [];
//        foreach ($hours as $hour) {
//            $endHours[] = $hour->format('H:i');
//        }
//        return $endHours;
//    }
//
//    private function formatPostalCode($postalCode): string
//    {
//        return str_replace([' ', '-'], '', $postalCode);
//    }

}
