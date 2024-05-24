<?php

namespace xGrz\Dhl24\Facades;

use Illuminate\Support\Facades\Facade;
use xGrz\Dhl24\Services\DHLConfigService;

class DHLConfig extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DHLConfigService::class;
    }



}
