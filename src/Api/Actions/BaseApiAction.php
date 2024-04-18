<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Interfaces\DHLApiCallableInterface;
use xGrz\Dhl24\Services\ConfigService;

abstract class BaseApiAction implements DHLApiCallableInterface
{
    // API method name. Can be overwritten by parent. Must be protected.
    protected ?string $serviceName = null;

    // Data wrapper for all public parent props. Can be overwritten by parent. Must be protected.
    protected ?string $dataWrapper = null;

    // Response class name, fully qualified. Can be overwritten by parent. Must be protected.
    protected ?string $responseClassName = null;

    /**
     * @throws DHL24Exception
     */
    public function call()
    {
        $serviceName = $this->serviceName();
        $responseClassName = $this->responseClassName();
        try {
            $result = (new ConfigService())->connection()->$serviceName(self::getPayload());
            return new $responseClassName($result);
        } catch (\SoapFault $e) {
            throw new DHL24Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function serviceName(): string
    {
        return $this->serviceName ?? self::guessServiceName();
    }

    private function guessServiceName()
    {
        return str((new \ReflectionClass($this))->getShortName())
            ->camel();
    }

    private function responseClassName(): string
    {
        return $this->responseClassName ?? self::guessResponseClassName();
    }

    private function guessResponseClassName(): string
    {
        $responseNamespace = str((new \ReflectionClass($this))->getNamespaceName())
            ->replace("\Actions", '\Responses')
            ->toString();
        return '\\' . $responseNamespace . '\\' . ucfirst(self::guessServiceName()) . 'Response';
    }

    private function getPayload(): array
    {
        $payloadData = [];
        $publicProps = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($publicProps as $prop) {
            $prop->getName() !== 'authData'
                ? $payloadData[$prop->getName()] = $prop->getValue($this)
                : $payloadData['authData'] = Config::getAuth();
        }
        $payloadData = json_decode(json_encode($payloadData), true);
        // dd($payloadData);
        return self::addPayloadWrapper($payloadData);
    }

    private function addPayloadWrapper(array $payloadData): array
    {
        if (empty($this->dataWrapper)) return $payloadData;
        return [
            $this->dataWrapper => $payloadData
        ];
    }
}
