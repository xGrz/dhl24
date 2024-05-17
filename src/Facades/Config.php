<?php

namespace xGrz\Dhl24\Facades;

use Illuminate\Support\Facades\Facade;
use xGrz\Dhl24\Services\ConfigService;

/**
 * @method static getDirectoryForConfirmations()
 * @method static getDiskForConfirmations()
 * @method static getApiPassword()
 * @method static getApiUsername()
 * @method static getAuth()
 * @method static getDefaultLabelType()
 * @method static getSapNumber()
 * @method static shouldUseIntelligentCostSaver()
 * @method static getIntelligentCostSaverMaxValue()
 * @method static shouldStoreLabels()
 */
class Config extends Facade
{


    protected static function getFacadeAccessor(): string
    {
        return ConfigService::class;
    }



}
