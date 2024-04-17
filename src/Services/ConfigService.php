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
}
