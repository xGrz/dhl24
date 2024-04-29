<?php

use xGrz\Dhl24\Enums\ShipmentItemType;

return [
    'type' => [
        ShipmentItemType::PACKAGE->name => 'Paczka do 31kg',
        ShipmentItemType::PALLET->name => 'Przesyłka paletowa',
        ShipmentItemType::ENVELOPE->name => 'Koperta/foliopak'
    ],
    'attributes' => [
        'type' => 'Typ przesyłki',
        'quantity' => 'ilość',
        'weight' => 'waga',
        'width' => 'szerokość',
        'height' => 'wysokość',
        'length' => 'długość',
        'nonStandard' => 'Niestandardowa'
    ],
    'address' => [
        'name' => 'Nazwa',
        'street' => 'Ulica',
        'house_number' => 'Numer',
        'postal_code' => 'Kod pocztowy',
        'city' => 'Miasto',
        'phone' => 'Telefon',
        'email' => 'Email',
    ],
    'services' => [
        'cod' => 'Pobranie',
        'content' => 'Zawartość',
        'owl' => 'Odbiór własny',
        'pdi' => 'Informacja przed dostawą',
        'rod' => 'Zwrot dokumentów',
        'reference' => 'Referencja',
        'value' => 'Wartość',
        'costsCenter' => 'Miejsce powstania kosztu',
        'comment' => 'Informacje'
    ]
];
