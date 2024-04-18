<?php

namespace xGrz\Dhl24\Api\Actions;

use Carbon\Carbon;
use xGrz\Dhl24\Api\Responses\GetMyShipmentsResponse;
use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class GetMyShipments extends BaseApiAction
{
    public AuthData $authData;
    public string $createdFrom;
    public string $createdTo;
    public ?string $offset = null;

    /**
     * @throws DHL24Exception
     */
    private function __construct(Carbon $from, Carbon $to, int $page = null)
    {
        $this->authData = new AuthData();
        $this->createdFrom = $from->format('Y-m-d');
        $this->createdTo = $to->format('Y-m-d');
        $this->offset = $page;
    }

    public function getResponseClassName(): string
    {
        return GetMyShipmentsResponse::class;
    }

    /**
     * @throws DHL24Exception
     */
    public static function make(Carbon $from, Carbon $to, int $page = null): static
    {
        return new static($from, $to, $page);
    }



}
