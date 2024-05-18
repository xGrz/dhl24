<?php

namespace xGrz\Dhl24\Api\Actions;


use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Api\Structs\ItemToPrint;
use xGrz\Dhl24\Enums\LabelType;

class GetLabel extends BaseApiAction
{
    protected ?string $serviceName = 'getLabels';

    public AuthData $authData;
    public array $itemsToPrint = [];

    private function __construct(string|int $shipmentNumber, ?LabelType $labelType = null)
    {
        $this->itemsToPrint[] = ItemToPrint::make($shipmentNumber);
        if ($labelType) self::setLabelType($labelType);
    }

    public function setLabelType(LabelType $labelType): static
    {
        foreach ($this->itemsToPrint as $key => $item) {
            $this->itemsToPrint[$key]->setType($labelType);
        }
        return $this;
    }

    public static function make(string|int $shipmentNumber, ?LabelType $labelType = null): self
    {
        return new self($shipmentNumber, $labelType);
    }
}
