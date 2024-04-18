<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Exceptions\DHL24Exception;

class GetVersion extends BaseApiAction
{

    /**
     * @throws DHL24Exception
     */
    public static function make(): static
    {
        return new static();
    }



}
