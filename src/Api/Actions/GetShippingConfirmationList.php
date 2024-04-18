<?php

namespace xGrz\Dhl24\Api\Actions;

use Carbon\Carbon;
use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Enums\ShippingConfirmationType;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class GetShippingConfirmationList extends BaseApiAction
{
    protected ?string $serviceName = 'getPnp';
    protected ?string $dataWrapper = 'pnpRequest';

    public AuthData $authData;
    public string $date;
    public string $type;

    /**
     * @throws DHL24Exception
     */
    private function __construct(Carbon $date = null, ShippingConfirmationType $type = ShippingConfirmationType::ALL)
    {
        $this->authData = new AuthData();
        $this->date = $date ? $date->format('Y-m-d') : now()->format('Y-m-d');
        $this->type = $type->name;
    }


    /**
     * @throws DHL24Exception
     */
    public static function make(Carbon $date = null, ShippingConfirmationType $type = ShippingConfirmationType::ALL): static
    {
        return new static($date, $type);
    }



}
