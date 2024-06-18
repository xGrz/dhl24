<?php

namespace xGrz\Dhl24\Services;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use xGrz\Dhl24\Actions\PostalCodeServices;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Models\DHLShipment;

class DHLPostalCodeService
{
    private string $postalCode = '';

    // default true for express shipments is required;
    private bool $expressShipments = true;

    private array $bookings = [];

    /**
     * @throws DHL24Exception
     */
    public function __construct(Collection|EloquentCollection|DHLShipment $shipments)
    {
        $postalCode = $shipments instanceof DHLShipment
            ? $shipments->shipper_postal_code
            : $shipments->first()->shipper_postal_code;

        $this->postalCode = str_replace([' ', '-'], '', $postalCode);
        self::setupShipmentType($shipments);
        $this->getBookingOptions();
    }

    public function getAvailableBookings(): array
    {
        return $this->bookings;
    }

    public function availableDates(): array
    {
        return array_keys($this->bookings);
    }

    public function pickupStartingOptions(Carbon $date)
    {
        $date = $date->format('d-m-Y');
        if (!in_array($date, array_keys($this->bookings))) return [];
        return $this->bookings[$date]['from'];
    }

    public function pickupEndingOptions(Carbon $from): array
    {
        if (!self::isPickupFromValid($from)) return [];
        return self::getBookingEnd(
            $from->format('H:i'),
            end($this->bookings[$from->format('d-m-Y')]['to'])
        );
    }

    private function isPickupFromValid(Carbon $from): bool
    {
        $pickupDate = $from->format('d-m-Y');
        $startTime = $from->ceilMinutes(DHLConfig::getBookingTimeInterval())->format('H:i');
        return in_array($startTime, $this->bookings[$pickupDate]['from']);
    }

    /**
     * @throws DHL24Exception
     */
    private function getBookingOptions(): void
    {
        if (!$this->postalCode) throw new DHL24Exception('Pickup postal code is required.', 1010);
        $pickupDate = now();
        for ($calls = 0; count($this->bookings) < 4 && $calls < 10; $calls++) {
            $bookingHours = self::bookingHours($pickupDate);
            if (count($bookingHours['from']) > 0 && count($bookingHours['to']) > 0) {
                $this->bookings[$pickupDate->format('d-m-Y')] = $bookingHours;
            }
            $pickupDate->addDay();
        }
    }

    /**
     * @throws DHL24Exception
     */
    private function bookingHours($pickupDate = null): array
    {
        if (!$this->postalCode) throw new DHL24Exception('Pickup postal code is required.', 1010);
        if (!$pickupDate) $pickupDate = $this->shipment?->shipment_date ?? now();

        $postalCodeServices = (new PostalCodeServices())->get($this->postalCode, $pickupDate);

        return $this->expressShipments
            ? [
                'from' => self::getBookingStart($postalCodeServices->exPickupStart(), $postalCodeServices->exPickupEnd()),
                'to' => self::getBookingEnd($postalCodeServices->exPickupStart(), $postalCodeServices->exPickupEnd())
            ]
            :
            [
                'from' => self::getBookingStart($postalCodeServices->drPickupStart(), $postalCodeServices->drPickupEnd()),
                'to' => self::getBookingEnd($postalCodeServices->drPickupStart(), $postalCodeServices->drPickupEnd())
            ];
    }

    private function getBookingStart(string $from, string $to): array
    {
        $from = Carbon::parse($from)->ceilMinutes(DHLConfig::getBookingTimeInterval());
        $to = Carbon::parse($to)->subHours(DHLConfig::getBookingWindow());
        $hours = CarbonPeriod::create($from, '15 minutes', $to)->toArray();

        $startHours = [];
        foreach ($hours as $hour) {
            $startHours[] = $hour->format('H:i');
        }
        return $startHours;
    }

    private function getBookingEnd(string $from, string $to): array
    {
        $from = Carbon::parse($from)->ceilMinutes(DHLConfig::getBookingTimeInterval())->addHours(DHLConfig::getBookingWindow());
        $to = Carbon::parse($to);
        $hours = CarbonPeriod::create($from, '15 minutes', $to)->toArray();

        $endHours = [];
        foreach ($hours as $hour) {
            $endHours[] = $hour->format('H:i');
        }
        return $endHours;
    }

    private function setupShipmentType(Collection|DHLShipment $shipments = null): void
    {

        if (!$shipments) return; // default true;
        if ($shipments instanceof DHLShipment) {
            $this->expressShipments = $shipments->isExpress();
            return;
        }
        foreach ($shipments as $shipment) {
            if(!$shipment->isExpress()) {
                $this->expressShipments = false;
            }
        }
        // if all shipments are express default true is not changed;
    }
}
