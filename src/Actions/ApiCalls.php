<?php

namespace xGrz\Dhl24\Actions;

use Illuminate\Support\Facades\Log;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHLConfig;

class ApiCalls
{
    protected string $method = '';
    protected string $wrapper = '';
    protected array $payload = [];

    protected function getPayload(): array
    {
        $payload = $this->payload;
        $payload['authData'] = DHLConfig::getAuth();
        if ($this->wrapper) {
            return [$this->wrapper => $payload];
        }
        return $payload;
    }

    /**
     * @throws DHL24Exception
     */
    protected function call()
    {
        $method = $this->method;
        try {
            return DHLConfig::connection()->$method($this->getPayload());
        } catch (\SoapFault $e) {
            Log::error('DHL error: ' . $e->getMessage(), $this->payload);
            throw new DHL24Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}
