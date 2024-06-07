<?php

namespace xGrz\Dhl24\Facades;

use Illuminate\Database\Eloquent\Builder;
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
use xGrz\Dhl24\Jobs\TrackShipmentJob;
use xGrz\Dhl24\Models\DHLContentSuggestion;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Models\DHLTrackingState;
use xGrz\Dhl24\Services\DHLContentService;
use xGrz\Dhl24\Services\DHLCostCenterService;
use xGrz\Dhl24\Services\DHLTrackingService;
use xGrz\Dhl24\Services\DHLTrackingStateService;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;

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

    public static function dhlShipments(Carbon $from = null, Carbon $to = null, int $page = 1): Collection
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

    public static function contentSuggestions(DHLContentSuggestion|int|null $suggestion = null): DHLContentService
    {
        return (new DHLContentService($suggestion));
    }

    public static function costsCenter(DHLCostCenter|int|null $costsCenter = null): DHLCostCenterService
    {
        return (new DHLCostCenterService($costsCenter));
    }

    public static function states(DHLTrackingState|string|null $status = null): DHLTrackingStateService
    {
        return new DHLTrackingStateService($status);
    }

    public static function wizard(DHLShipment $shipment = null): DHLShipmentWizard
    {
        return new DHLShipmentWizard($shipment);
    }

    public static function shipments(bool $withRelations = true): Builder
    {
        return $withRelations
            ? DHLShipment::withDetails()
            : DHLShipment::query();
    }

    public static function shipment(DHLShipment|int $shipment): ?DHLShipment
    {
        if ($shipment instanceof DHLShipment) return $shipment->loadMissing(DHLShipment::getRelationsListForDetails());
        return DHLShipment::withDetails()->find($shipment)
            ?? DHLShipment::withDetails()->where('number', $shipment)->first();
    }

    /**
     * @throws DHL24Exception
     */
    public static function trackAllShipments(bool $shouldBeDispatchedAsJob = true): void
    {
        foreach (DHLTrackingService::getUndeliveredShipments() as $shipment) {
            self::trackShipment($shipment, $shouldBeDispatchedAsJob);
        }
    }


    /**
     * @throws DHL24Exception
     */
    public static function trackShipment(DHLShipment|string|int $shipment, bool $shouldDispatchJob = true): void
    {
        $shouldDispatchJob
            ? TrackShipmentJob::dispatch($shipment)->onQueue(DHLConfig::getQueueName())
            : (new DHLTrackingService($shipment));
    }

    public static function getShipment(DHLShipment|string|int $shipment): ?DHLShipment
    {
        if ($shipment instanceof DHLShipment) return $shipment;
        return DHLShipment::where('number', $shipment)->first()
            ?? DHLShipment::find($shipment)->first();
    }

}
