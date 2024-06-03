<?php

use xGrz\Dhl24\Enums\DHLStatusType;

return [
    DHLStatusType::NOT_FOUND->name => 'Nie znaleziono',
    DHLStatusType::CREATED->name => 'Utworzona',
    DHLStatusType::SENT->name => 'Odebrana przez kuriera',
    DHLStatusType::IN_TRANSPORT->name => 'Przesyłka w drodze',
    DHLStatusType::IN_DELIVERY->name => 'W doręczeniu',
    DHLStatusType::HOLD->name => 'Wstrzymana',
    DHLStatusType::WAITING_TO_BE_PICKED_UP->name => 'Czeka na odbiór',
    DHLStatusType::DELIVERED->name => 'Dostarczona',
    DHLStatusType::PICKED_UP->name => 'Odebrana',
    DHLStatusType::DELIVERY_FAILED->name => 'Nieudane doręczenie',
    DHLStatusType::RETURNED->name => 'Zwrot do nadawcy',
    DHLStatusType::ERROR->name => 'Błąd przesyłki',
];
