<?php

namespace xGrz\Dhl24\Api\Structs;

use xGrz\Dhl24\Enums\LabelType;
use xGrz\Dhl24\Facades\Config;

class ItemToPrint
{
    public string $labelType;
    public string|int $shipmentId;

    private function __construct(string|int $shipmentId, LabelType $labelType = null)
    {
        $this->shipmentId = $shipmentId;
        $this->setType(
            $labelType ?: Config::getDefaultLabelType()
        );

    }

    public function setType(LabelType $labelType): static
    {
        $this->labelType = $labelType->name;
        return $this;
    }

    public static function make(string|int $shipmentId, LabelType $labelType = null): ItemToPrint
    {
        return new self($shipmentId, $labelType);
    }
}
