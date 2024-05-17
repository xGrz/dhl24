<?php

namespace xGrz\Dhl24\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Listeners\GetShipmentCostListener;
use xGrz\Dhl24\Listeners\GetShipmentLabelListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ShipmentCreatedEvent::class => [
            GetShipmentLabelListener::class,
            GetShipmentCostListener::class,
        ]
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
