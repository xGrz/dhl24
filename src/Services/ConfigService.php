<?php

namespace xGrz\Dhl24\Services;

use SoapClient;
use xGrz\Dhl24\Api\Structs\AuthData;

class ConfigService
{
    public function connection(): SoapClient
    {
        try {
            return new SoapClient(env('DHL24_URL'));
        } catch (\SoapFault $e) {
            dd($e->getMessage());
        }
    }

    public static function getAuth(): AuthData
    {
        return new AuthData();
    }
}
