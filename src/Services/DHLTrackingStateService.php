<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Builder;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLTrackingState;

class DHLTrackingStateService
{

    private ?DHLTrackingState $status = null;

    public function __construct(DHLTrackingState|string|null $status = null)
    {
        if ($status) $this->status = self::loadStatus($status);
    }

    public function query(): Builder
    {
        return DHLTrackingState::query();
    }

    public function get(): ?DHLTrackingState
    {
        return $this->status;
    }

    public function create(string $symbol, string $description): static
    {
        DHLTrackingState::create(['symbol' => $symbol, 'description' => $description]);
        return $this;
    }

    public function exists(string $symbol): bool
    {
        return DHLTrackingState::where('symbol', $symbol)->count();
    }

    public function rename(string $name): static
    {
        $this->status->update(['description' => $name]);
        return $this;
    }

    public function setType(DHLStatusType $type): static
    {
        $this->status->update(['type' => $type]);
        return $this;
    }

    public function getTypeOptions(): array
    {
        return DHLStatusType::getOptions();
    }

    public function findForTracking(string $statusSymbol, string $description = null): DHLTrackingState
    {
        $this->status = DHLTrackingState::updateOrCreate(
            ['code' => $statusSymbol],
            ['system_description' => $description],
        );
        return $this->status;
    }

    private function loadStatus(DHLTrackingState|string $status): DHLTrackingState
    {
        return $status instanceof DHLTrackingState
            ? $status
            : DHLTrackingState::where('code', $status)->first();
    }
}
