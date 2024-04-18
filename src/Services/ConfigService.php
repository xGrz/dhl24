<?php

namespace xGrz\Dhl24\Services;

use SoapClient;
use xGrz\Dhl24\Api\Structs\AuthData;

class ConfigService
{
    /**
     * @throws \SoapFault
     */
    public function connection(): SoapClient
    {
        return new SoapClient(env('DHL24_URL'));
    }

    public static function getAuth(): AuthData
    {
        return new AuthData();
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

    public function getDiskForShippingLabels(): string
    {
        return config('dhl24.labels.disk', 'local');
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

    private static function normalizeDirectoryPath(string $path): string
    {
        return str($path)
            ->replaceStart('/', '')
            ->replaceEnd('/', '')
            ->append('/');
    }

}
