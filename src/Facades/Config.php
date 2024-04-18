<?php

namespace xGrz\Dhl24\Facades;

use Illuminate\Support\Facades\Facade;
use xGrz\Dhl24\Services\ConfigService;

/**
 * @method static getDirectoryForConfirmations()
 * @method static getDiskForConfirmations()
 */
class Config extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ConfigService::class;
    }



}
