<?php

namespace xGrz\Dhl24\Wizard;

use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Enums\ShipmentType;
use xGrz\Dhl24\Wizard\Components\Address\ReceiverAddress;
use xGrz\Dhl24\Wizard\Components\Address\ShipperAddress;
use xGrz\Dhl24\Wizard\Components\Item;
use xGrz\Dhl24\Wizard\Components\ServiceDefinition;
use xGrz\Dhl24\Wizard\Components\Shipment;

class ShipmentWizard
{
    private Shipment $shipment;

    public function __construct(ShipmentType $shipmentType = ShipmentType::DOMESTIC)
    {
        $this->shipment = new Shipment();
        $this->shipment->shipper = new ShipperAddress();
        $this->shipment->receiver = new ReceiverAddress();
        $this->shipment->service = new ServiceDefinition($shipmentType);
    }

    public function shipper(): ShipperAddress
    {
        return $this->shipment->shipper;
    }

    public function receiver(): ReceiverAddress
    {
        return $this->shipment->receiver;
    }

    public function services(): ServiceDefinition
    {
        return $this->shipment->service;
    }

    public function addItem(
        ShipmentItemType $type = ShipmentItemType::PACKAGE,
        int              $quantity = 1,
        ?int              $width = null,
        ?int              $height = null,
        ?int              $length = null,
        ?float            $weight = null,
        bool $isNonStandard = null,
        bool $euroReturn = null
    ): Item
    {
        $item = new Item($type);
        $item->setQuantity($quantity);
        if(!empty($width)) $item->setWidth($width);
        if(!empty($height)) $item->setHeight($height);
        if(!empty($length)) $item->setLength($length);
        if(!empty($weight)) $item->setWeight($weight);
        if($isNonStandard) $item->setNonStandard();
        if($euroReturn) $item->setEuroReturn();

        $this->shipment->pieceList[] = $item;
        return $item;
    }


    public function toArray(): array
    {
        return $this->shipment->toArray();
    }


}
