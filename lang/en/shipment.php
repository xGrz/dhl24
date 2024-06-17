<?php

use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;

return [
    'shipment-type' => [
        DHLDomesticShipmentType::DOMESTIC->name => 'Domestic shipment',
        DHLDomesticShipmentType::PREMIUM->name => 'Premium shipment',
        DHLDomesticShipmentType::DOMESTIC09->name => 'Delivery before 9:00am',
        DHLDomesticShipmentType::DOMESTIC12->name => 'Delivery before 12:00am',
        DHLDomesticShipmentType::EVENING_DELIVERY->name => 'Evening delivery'
    ],
    'package-type' =>  [
        DHLShipmentItemType::ENVELOPE->name => 'Envelope',
        DHLShipmentItemType::PACKAGE->name => 'Package up to 30kg',
        DHLShipmentItemType::PALLET->name => 'Pallet',
    ],
    'services' => [
        'content' => 'Content'
    ]

];
