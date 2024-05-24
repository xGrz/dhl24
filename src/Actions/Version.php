<?php

namespace xGrz\Dhl24\Actions;

use xGrz\Dhl24\Exceptions\DHL24Exception;

class Version extends ApiCalls
{
    protected string $method = 'GetVersion';
    protected array $payload = [];

    /**
     * @throws DHL24Exception
     */
    public function get()
    {
        return $this->call()?->getVersionResult;
    }

}
