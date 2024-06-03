<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Builder;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLStatus;

class DHLStateService
{

    private ?DHLStatus $status = null;

    public function __construct(DHLStatus|string|null $status = null)
    {
        if ($status) $this->status = self::loadStatus($status);
    }

    public function query(): Builder
    {
        return DHLStatus::query();
    }

    public function create(string $symbol, string $description): static
    {
        DHLStatus::create(['symbol' => $symbol, 'description' => $description]);
        return $this;
    }

    public function exists(string $symbol): bool
    {
        return DHLStatus::where('symbol', $symbol)->count();
    }

    public function rename(string $name): static
    {
        $this->status->update(['custom_description' => $name]);
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


    private function loadStatus(DHLStatus|string $status): DHLStatus
    {
        return $status instanceof DHLStatus
            ? $status
            : DHLStatus::where('symbol', $status)->first();
    }
}
