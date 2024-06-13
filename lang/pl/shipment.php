<?php

use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;

return [
    'shipment-type' => [
        DHLDomesticShipmentType::DOMESTIC->name => 'Przesyłka krajowa',
        DHLDomesticShipmentType::PREMIUM->name => 'Przesyłka premium',
        DHLDomesticShipmentType::DOMESTIC09->name => 'Dostawa do 9:00',
        DHLDomesticShipmentType::DOMESTIC12->name => 'Dostawa do 12:00',
        DHLDomesticShipmentType::EVENING_DELIVERY->name => 'Doręczenie wieczorne'
    ],
    'package-type' =>  [
        DHLShipmentItemType::ENVELOPE->name => 'Koperta/Foliopak',
        DHLShipmentItemType::PACKAGE->name => 'Paczka do 30kg',
        DHLShipmentItemType::PALLET->name => 'Paleta',
    ],
    'services' => [
        'content' => 'Zawartość'
    ]

];
