<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLTrackingState;

class DHLTrackingStatusService
{

    private DHLTrackingState $status;

    public function __construct(DHLTrackingState|string $status) {
        $this->status = self::getStatus($status);
    }

    public static function getStates(): EloquentCollection
    {
        return DHLTrackingState::orderByTypes()->get();
    }

    public static function findForTracking(string $statusSymbol, string $description = null): DHLTrackingState
    {
        return DHLTrackingState::updateOrCreate(
            ['code' => $statusSymbol],
            ['system_description' => $description],
        );
    }

    public static function getState(DHLTrackingState|string $status): DHLTrackingState
    {
        return self::getStatus($status);
    }

    public function updateType(DHLStatusType $type): static
    {
        $this->status->update(['type' => $type]);
        return $this;
    }

    public function updateDescription(string $description = null): static
    {
        $this->status->update(['description' => $description]);
        return $this;
    }

    private static function getStatus(DHLTrackingState|string $status): DHLTrackingState
    {
        return $status instanceof DHLTrackingState
            ? $status
            : DHLTrackingState::where('code', $status)->first();
    }

    public static function getStatusTypes(): array
    {
        return DHLStatusType::getOptions();
    }
}
