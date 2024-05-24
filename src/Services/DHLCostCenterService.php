<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Traits\HandlesPagination;

class DHLCostCenterService
{

    use HandlesPagination;

    public static function getCostCenters(bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return self::applyPagination(DHLCostCenter::sorted(), $withPagination, $paginationName);
    }

    public static function getDeletedCostCenters(bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return self::applyPagination(DHLCostCenter::onlyTrashed()->sorted(), $withPagination, $paginationName);
    }

    public static function getAllCostCenters(bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return self::applyPagination(DHLCostCenter::withTrashed()->sorted(), $withPagination, $paginationName);
    }

    public static function getShipmentsByCostCenter(DHLCostCenter|int $center, bool|int $withPagination = false, string $paginationName = null)
    {
        return self::costCenter($center)
            ->load([
                'shipments' => function ($shipments) use ($withPagination, $paginationName) {
                    self::applyPagination($shipments, $withPagination, $paginationName);
                }
            ])->shipments;
    }

    /**
     * @throws DHL24Exception
     */
    public static function add(string $name)
    {
        if ($exists = self::isNameExists($name)) {
            $exists->deleted_at
                ? throw new DHL24Exception('This cost center name was already used and deleted', 101)
                : throw new DHL24Exception('This cost center name exists', 102);
        }
        return DHLCostCenter::create(['name' => $name])->save();
    }

    /**
     * @throws DHL24Exception
     */
    public static function rename(DHLCostCenter|int $center, string $name): bool
    {
        if ($exists = self::isNameExists($name)) {
            $exists->deleted_at
                ? throw new DHL24Exception('This cost center name was already used and deleted', 101)
                : throw new DHL24Exception('This cost center name exists', 102);
        }
        return self::costCenter($center)->update(['name' => $name]);
    }

    public static function delete(DHLCostCenter|int $center): ?bool
    {
        $center = self::costCenter($center);
        if (!$center) return false;
        $center->loadMissing(['shipments']);
        return ($center->shipments->isEmpty())
            ? $center->forceDelete()
            : $center->delete();
    }

    public static function restore(DHLCostCenter|int $center): bool|int
    {
        return self::costCenter($center)->restore();
    }

    public static function setDefault(DHLCostCenter|int $center): bool
    {
        return self::costCenter($center)
            ->update(['is_default' => true]);

    }

    private static function isNameExists(string $name): ?DHLCostCenter
    {
        return DHLCostCenter::withTrashed()
            ->where('name', $name)
            ->first();
    }

    private static function costCenter(DHLCostCenter|int $center): ?DHLCostCenter
    {
        return $center instanceof DHLCostCenter
            ? $center
            : DHLCostCenter::withTrashed()->find($center);
    }
}
