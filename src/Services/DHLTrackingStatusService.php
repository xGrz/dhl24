<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLStatus;

class DHLTrackingStatusService
{

    private DHLStatus $status;

    public function __construct(DHLStatus|string $status) {
        $this->status = self::getStatus($status);
    }

    public static function getStates(): EloquentCollection
    {
        return DHLStatus::orderByTypes()->get();
    }

    public static function findForTracking(string $statusSymbol, string $description = null): DHLStatus
    {
        return DHLStatus::updateOrCreate(
            ['symbol' => $statusSymbol],
            ['description' => $description],
        );
    }

    public static function getState(DHLStatus|string $status): DHLStatus
    {
        return self::getStatus($status);
    }

    public function updateType(DHLStatusType $type): static
    {
        $this->status->update(['type' => $type]);
        return $this;
    }

    public function updateDescription(string $custom_description = null): static
    {
        $this->status->update(['custom_description' => $custom_description]);
        return $this;
    }

    private static function getStatus(DHLStatus|string $status): DHLStatus
    {
        return $status instanceof DHLStatus
            ? $status
            : DHLStatus::where('symbol', $status)->first();
    }

    public static function getStatusTypes(): array
    {
        return DHLStatusType::getOptions();
    }
}
