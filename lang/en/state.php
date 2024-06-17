<?php

use xGrz\Dhl24\Enums\DHLStatusType;

return [
    DHLStatusType::NOT_FOUND->name => 'Not found',
    DHLStatusType::CREATED->name => 'Created',
    DHLStatusType::SENT->name => 'Picked up',
    DHLStatusType::IN_TRANSPORT->name => 'In transport',
    DHLStatusType::IN_DELIVERY->name => 'In delivery',
    DHLStatusType::HOLD->name => 'Held',
    DHLStatusType::WAITING_TO_BE_PICKED_UP->name => 'Waiting to be picked up',
    DHLStatusType::DELIVERED->name => 'Delivered',
    DHLStatusType::PICKED_UP->name => 'Picked up',
    DHLStatusType::DELIVERY_FAILED->name => 'Delivery failed',
    DHLStatusType::RETURNED->name => 'Returned',
    DHLStatusType::ERROR->name => 'Error',
];
