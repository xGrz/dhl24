<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Database\Eloquent\Builder;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Traits\HandlesPagination;

class DHLCostCenterService
{

    use HandlesPagination;

    private ?DHLCostCenter $costsCenter = null;

    public function __construct(DHLCostCenter|int|null $costsCenter = null)
    {
        if ($costsCenter) $this->costsCenter = self::loadCostCenter($costsCenter);
    }

    public static function query(): Builder
    {
        return DHLCostCenter::query()->sorted();
    }

    /**
     * @throws DHL24Exception
     */
    public function add(string $name): static
    {
        if ($exists = self::isNameExists($name)) {
            $exists->deleted_at
                ? throw new DHL24Exception('This cost center name was already used and deleted', 101)
                : throw new DHL24Exception('This cost center name exists', 102);
        }
        DHLCostCenter::create(['name' => $name])->save();
        return $this;
    }

    /**
     * @throws DHL24Exception
     */
    public function rename(string $name): static
    {
        if ($exists = self::isNameExists($name)) {
            $exists->deleted_at
                ? throw new DHL24Exception('This cost center name was already used and deleted', 101)
                : throw new DHL24Exception('This cost center name exists', 102);
        }
        $this->costsCenter->update(['name' => $name]);
        return $this;
    }

    public function delete(): ?static
    {
        $this->costsCenter->loadMissing(['shipments']);
        $this->costsCenter->shipments->isEmpty()
            ? $this->costsCenter->forceDelete()
            : $this->costsCenter->delete();
        return $this;
    }

    public function setDefault(): static
    {
        $this->costsCenter->update(['is_default' => true]);
        return $this;
    }

    public function restore(): static
    {
        $this->costsCenter->restore();
        return $this;
    }


    public function shipments(): Builder
    {
        return DHLShipment::query()->where('cost_center_id', $this->costsCenter->id);
    }

    private static function isNameExists(string $name): ?DHLCostCenter
    {
        return DHLCostCenter::withTrashed()
            ->where('name', $name)
            ->first();
    }

    private static function loadCostCenter(DHLCostCenter|int $center): ?DHLCostCenter
    {
        return $center instanceof DHLCostCenter
            ? $center
            : DHLCostCenter::withTrashed()->find($center);
    }
}
