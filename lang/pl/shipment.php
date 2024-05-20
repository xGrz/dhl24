<?php

use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Enums\StatusType;

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
    ],
    'service' => [
        'DOMESTIC' => 'Paczka krajowa',
        'PREMIUM' => 'Paczka premium',
        'DOMESTIC09' => 'Doręczenie do 9:00',
        'DOMESTIC12' => 'Doręczenie do 12:00',
        'EVENING_DELIVERY' => 'Doręczenie wieczorem'
    ],
    'statusType' => [
        StatusType::NEW->name => 'Utworzona',
        StatusType::PICKED_UP->name => 'Odebrana przez kuriera',
        StatusType::ARRIVED->name => 'Przyjęcie w terminalu',
        StatusType::DEPARTED->name => 'Wyjście z terminala',
        StatusType::SORTING->name => 'W sortowni',
        StatusType::OUT_FOR_DELIVERY->name => 'Wydanie do doręczenia',
        StatusType::HOLD->name => 'Wstrzymana',
        StatusType::FAILED->name => 'Nieudane doręczenie',
        StatusType::RETURNED->name => 'Zwrot do nadawcy',
        StatusType::DELIVERED->name => 'Dostarczona',
        StatusType::READY_FOR_PICK_UP->name => 'Czeka na odbiór',
        StatusType::ERROR->name => 'Nie znaleziono',
    ],
];
