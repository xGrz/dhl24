<?php

namespace xGrz\Dhl24\Services;

use SoapClient;
use xGrz\Dhl24\APIStructs\AuthData;
use xGrz\Dhl24\Enums\DHLLabelType;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class DHLConfigService
{
    /**
     * @throws \SoapFault
     */
    public function connection(): SoapClient
    {
        return new SoapClient(config('dhl24.auth.wsdl'));
    }
    public function getAuth(): AuthData
    {
        return new AuthData();
    }
    public function getSapNumber(): string
    {
        return config('dhl24.auth.sap');
    }
    public function getApiUsername(): string
    {
        return config('dhl24.auth.username');
    }
    public function getApiPassword(): string
    {
        return config('dhl24.auth.password');
    }
    public function getDiskForReports(): string|false
    {
        return config('dhl24.reports.disk', 'local');
    }
    public function shouldStoreReports(): bool
    {
        return self::getDiskForReports() !== false;
    }
    public function getDirectoryForReports(): string
    {
        return self::normalizeDirectoryPath(
            config(
                'dhl24.reports.directory',
                'dhl/reports'
            )
        );
    }
    public function getDiskForLabels(): string|false
    {
        return config('dhl24.labels.disk', 'local');
    }
    public function shouldStoreLabels(): bool
    {
        return self::getDiskForLabels() !== false;
    }
    public function getDirectoryForLabels(): string
    {
        return self::normalizeDirectoryPath(
            config(
                'dhl24.labels.directory',
                'dhl/labels'
            )
        );
    }

    /**
     * @throws DHL24Exception
     */
    public function getDefaultLabelType(): DHLLabelType
    {
        $labelTypeName = config('dhl24.labels.defaultType', DHLLabelType::LP->name);
        return DHLLabelType::findByName($labelTypeName);
    }
    public function getShipmentInsuranceValueRounding(): int
    {
        return config('dhl24.shipment-insurance.insurance_value_round_up', 0);
    }
    public function shouldUseIntelligentCostSaver(): bool
    {
        return config('dhl24.shipment-insurance.intelligent_cost_saver', false);
    }
    public function getIntelligentCostSaverMaxValue(): int
    {
        return config('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 0);
    }

    public function getTrackingMaxShipmentAge(): int
    {
        return config('dhl24.track_shipment_max_age', 14);
    }

    public function getQueueName()
    {
        return config('dhl24.queue', 'default');
    }

    public function getRestrictionCheckSetting(): bool
    {
        return !config('dhl24.restrictions-check', false);
    }
    private static function normalizeDirectoryPath(string $path): string
    {
        return str($path)
            ->replaceStart('/', '')
            ->replaceEnd('/', '')
            ->append('/');
    }



}
