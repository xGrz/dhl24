<?php

namespace xGrz\Dhl24\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use xGrz\Dhl24\Actions\PostalCodeServices;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Models\DHLShipment;

class DHLPostalCodeService
{
    private string $postalCode = '';
    private array $bookings = [];

    /**
     * @throws DHL24Exception
     */
    public function __construct(string $postalCode = null)
    {
        $this->postalCode = $postalCode;
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
        dump($pickupDate, $startTime);
        return in_array($startTime, $this->bookings[$pickupDate]['from']);
    }

    private function getBookingOptions(int $maxDays = 5): void
    {
        if ($maxDays > 5) $maxDays = 5; // performance: max 5 days;
        if (!$this->postalCode) throw new DHL24Exception('Pickup postal code is required.', 1010);
        $pickupDate = now();
        for ($calls = 0; count($this->bookings) < $maxDays && $calls < 10; $calls++) {
            $bookingHours = self::bookingHours($this->postalCode, $pickupDate);
            if (count($bookingHours['from']) > 0 && count($bookingHours['to']) > 0) {
                $this->bookings[$pickupDate->format('d-m-Y')] = $bookingHours;
            }
            $pickupDate->addDay();
        }
    }

    private function bookingHours(string $postalCode = null, Carbon $pickupDate = null): array
    {
        $postalCode = $postalCode ? self::formatPostalCode($postalCode) : $this->shipment->shipper_postal_code;
        if (!$postalCode) throw new DHL24Exception('Pickup postal code is required.', 1010);
        if (!$pickupDate) $pickupDate = $this->shipment?->shipment_date ?? now();

        $postalCodeServices = (new PostalCodeServices())->get($postalCode, $pickupDate);
        return [
            'from' => self::getBookingStart($postalCodeServices->exPickupStart(), $postalCodeServices->exPickupEnd()),
            'to' => self::getBookingEnd($postalCodeServices->exPickupStart(), $postalCodeServices->exPickupEnd())
        ];
    }

    public static function forPostalCode(string $postalCode): static
    {
        return new static(self::formatPostalCode($postalCode));
    }

    public static function forShipment(DHLShipment $shipment): static
    {
        return new static($shipment->shipper_postal_code);
    }

    private static function formatPostalCode(string $postalCode): string
    {
        return str_replace([' ', '-'], '', $postalCode);
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


}
