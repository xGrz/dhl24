<?php

namespace xGrz\Dhl24\Traits;

trait Addressable
{

    protected function fullCityBuilder(string $city, string|int $postalCode): string
    {
        return join(' ', [
            self::postalCodeFormatter($postalCode),
            $city
        ]);
    }

    protected function fullStreetBuilder(string $streetName, string|int $houseNumber = null, string|int $apartmentNumber = null): string
    {
        return join(' ', [
            $this->street,
            self::houseNumberWithApartmentFormatter($houseNumber, $apartmentNumber),
        ]);
    }

    protected function houseNumberWithApartmentFormatter(?string $houseNumber = null, ?string $apartmentNumber = null): string
    {
        return empty($apartmentNumber)
        ? $houseNumber
        : join('/', [$houseNumber, $apartmentNumber]);
    }

    protected function postalCodeFormatter(string|int $postCode): string
    {
        if (!is_numeric($postCode)) return $postCode;
        if (strlen($postCode) !== 5) return $postCode;

        return join('-', [
            str($postCode)->substr(0, 2),
            str($postCode)->substr(2, 3),
        ]);
    }

    protected function postalCodeToNumber(string $postCode): string
    {
        $postCode = trim($postCode);
        $formatted = (string) preg_replace('/[^0-9]/', '', $postCode);
        return strlen($formatted) === 5
            ? $formatted
            : $postCode;
    }

}
