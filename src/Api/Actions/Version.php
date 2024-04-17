<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Services\ConfigService;

class Version
{
    public static function getVersion(): ?string
    {
        $response = (new ConfigService())->connection()->getVersion();
        return $response?->getVersionResult ?? null;
    }
}
