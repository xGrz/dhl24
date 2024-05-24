<?php

namespace xGrz\Dhl24\Facades;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use xGrz\Dhl24\Actions\Label;
use xGrz\Dhl24\Actions\MyShipments;
use xGrz\Dhl24\Actions\ServicePoints;
use xGrz\Dhl24\Actions\ShipmentsReport;
use xGrz\Dhl24\Actions\Version;
use xGrz\Dhl24\Enums\DHLLabelType;
use xGrz\Dhl24\Enums\DHLServicePointType;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLContentSuggestion;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Services\DHLContentService;
use xGrz\Dhl24\Services\DHLCostCenterService;

class DHL24
{

    /**
     * @throws DHL24Exception
     */
    public static function apiVersion(): string
    {
        return (new Version())->get();
    }

    /**
     * @throws DHL24Exception
     */
    public static function servicePoints(string $postalCode, int $radius = 5, string $country = 'PL', DHLServicePointType $type = null): Collection
    {
        return (new ServicePoints())
            ->setPostalCode($postalCode)
            ->setRadius($radius)
            ->setCountry($country)
            ->get($type);
    }

    public static function myShipments(Carbon $from = null, Carbon $to = null, int $page = 1): Collection
    {
        return (new MyShipments())->get($from, $to, $page);
    }

    public static function report(Carbon $date = null, string $type = 'ALL'): ?ShipmentsReport
    {
        return (new ShipmentsReport)
            ->setDate($date ?? now())
            ->setType($type)
            ->get();
    }

    public static function label(DHLShipment|string|int $shipment = null, DHLLabelType $type = null): ?Label
    {
        return (new Label())
            ->setShipment($shipment)
            ->setType($type)
            ->get();
    }

    public static function contentSuggestions(): array
    {
        return DHLContentService::getContents()
            ->map(fn($suggestion) => [
                'id' => $suggestion->id,
                'name' => $suggestion->name,
                'is_default' => $suggestion->is_default
            ])
            ->toArray();
    }

    /**
     * @throws DHL24Exception
     */
    public static function addContentSuggestion(string $name)
    {
        return DHLContentService::add($name);
    }

    /**
     * @throws DHL24Exception
     */
    public static function renameContentSuggestion(DHLContentSuggestion|int $suggestion, string $name): bool
    {
        return DHLContentService::rename($suggestion, $name);
    }

    public static function deleteContentSuggestion(DHLContentSuggestion|int $suggestion): ?bool
    {
        return DHLContentService::delete($suggestion);
    }

    public static function setDefaultContent(DHLContentSuggestion|int $suggestion): ?bool
    {
        return DHLContentService::delete($suggestion);
    }


    public static function costsCenter(bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return DHLCostCenterService::getCostCenters($withPagination, $paginationName);
    }
    public static function deletedCostsCenter(bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return DHLCostCenterService::getDeletedCostCenters($withPagination, $paginationName);
    }
    public static function allCostCenters(bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return DHLCostCenterService::getAllCostCenters($withPagination, $paginationName);
    }
    public static function costCenterShipments(DHLCostCenter|int $center, bool|int $withPagination = false, string $paginationName = null): EloquentCollection|LengthAwarePaginator
    {
        return DHLCostCenterService::getShipmentsByCostCenter($center, $withPagination, $paginationName);
    }

    /**
     * @throws DHL24Exception
     */
    public static function addCostCenter(string $name)
    {
        return DHLCostCenterService::add($name);
    }

    /**
     * @throws DHL24Exception
     */
    public static function renameCostCenter(DHLCostCenter|int $center, string $name): bool
    {
        return DHLCostCenterService::rename($center, $name);
    }

    public static function deleteCostCenter(DHLCostCenter|int $center): ?bool
    {
        return DHLCostCenterService::delete($center);
    }

    public static function restoreCostCenter(DHLCostCenter|int $center): bool|int
    {
        return DHLCostCenterService::restore($center);
    }

    public static function setDefaultCostCenter(DHLCostCenter|int $center): bool
    {
        return DHLCostCenterService::setDefault($center);
    }


}
