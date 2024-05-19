<?php

namespace xGrz\Dhl24\Livewire\Settings\TrackingEvents;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Models\DHLStatus;

class TrackingEventListing extends Component
{
    public Collection $events;

    public function mount(): void
    {
        self::loadEvents();
    }

    public function render(): View
    {
        return view('dhl::settings.livewire.tracking-events.tracking-event-listing');
    }

    private function loadEvents(): void
    {
        $this->events = DHLStatus::orderByTypes()->get();
    }

}
