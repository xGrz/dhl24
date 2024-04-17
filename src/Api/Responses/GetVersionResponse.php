<?php

namespace xGrz\Dhl24\Api\Responses;

class GetVersionResponse
{
    private string $version;

    public function __construct(object $result)
    {
        $this->version = $result->getVersionResult;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
