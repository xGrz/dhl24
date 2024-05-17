<?php

namespace xGrz\Dhl24\Services;

use SoapClient;
use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Enums\LabelType;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class ConfigService
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

    public function getDiskForConfirmations(): string
    {
        return config('dhl24.shipping-confirmations.disk', 'local');
    }

    public function getDirectoryForConfirmations(): string
    {
        return self::normalizeDirectoryPath(
            config(
                'dhl24.shipping-confirmations.directory',
                'dhl/shipping-confirmations'
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
                'dhl/shipping-confirmations'
            )
        );
    }

    /**
     * @throws DHL24Exception
     */
    public function getDefaultLabelType(): LabelType
    {
        $labelTypeName = config('dhl24.labels.defaultType', LabelType::LP->name);
        return LabelType::findByName($labelTypeName);
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



    private static function normalizeDirectoryPath(string $path): string
    {
        return str($path)
            ->replaceStart('/', '')
            ->replaceEnd('/', '')
            ->append('/');
    }



}
