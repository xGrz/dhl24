<?php

namespace xGrz\Dhl24\APIStructs;

use Illuminate\Support\Carbon;
use xGrz\Dhl24\Models\DHLTrackingState;
use xGrz\Dhl24\Services\DHLTrackingStateService;

class TrackingEvent
{
    public DHLTrackingState $state;
    public Carbon $timestamp;

    public function __construct(public string $code, public ?string $terminal, Carbon|string $timestamp, string $system_description = null)
    {
        self::processTimestamp($timestamp);
        self::getTrackingStateModel($system_description);
    }

    private function processTimestamp(Carbon|string $timestamp): void
    {
        $this->timestamp = $timestamp instanceof Carbon
            ? $timestamp->setMicro(0)
            : Carbon::parse($timestamp)->setMicro(0);
    }

    private function getTrackingStateModel(string|null $system_description): void
    {
        $this->state = (new DHLTrackingStateService())->findForTracking($this->code, $system_description);
    }


}
