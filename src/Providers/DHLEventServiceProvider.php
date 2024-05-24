<?php

namespace xGrz\Dhl24\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;

class DHLEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        ShipmentCreatedEvent::class => [
//            GetShipmentLabelListener::class,
//            GetShipmentCostListener::class,
        ],
//        ShipmentSentEvent::class => [
//
//        ],
//        ShipmentDeliveredEvent::class => [
//
//        ],
    ];


    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
