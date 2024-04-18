<?php

namespace xGrz\Dhl24\Traits;

trait Addressable
{

    private function fullCityBuilder(string $city, string|int $postalCode): string
    {
        return join(' ', [
            self::postalCodeFormatter($postalCode),
            $city
        ]);
    }

    private function fullStreetBuilder(string $streetName, string|int $houseNumber = null, string|int $apartmentNumber = null): string
    {
        $fullStreet = join(' ', [
            $this->street,
            $this->houseNumber,
        ]);
        if (!empty($apartmentNumber)) $fullStreet = $fullStreet . '/' . $apartmentNumber;

        return $fullStreet;
    }

    private function postalCodeFormatter(string $postCode): string
    {
        // todo: protection for invalid postcode
        return join('-', [
            str($postCode)->substr(0, 2),
            str($postCode)->substr(2, 3),
        ]);
    }

}
