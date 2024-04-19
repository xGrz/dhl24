<?php

namespace xGrz\Dhl24\Api\Structs;

use Illuminate\Support\Carbon;
use xGrz\Dhl24\Api\Structs\Shipment\Item;
use xGrz\Dhl24\Api\Structs\Shipment\ReceiverAddress;
use xGrz\Dhl24\Api\Structs\Shipment\ServiceDefinition;
use xGrz\Dhl24\Api\Structs\Shipment\ShipperAddress;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Enums\ShipmentType;

class Shipment
{
    public ShipperAddress $shipper;
    public ReceiverAddress $receiver;
    public array $pieceList = [];
    public ServiceDefinition $service;
    public string $shipmentDate;
    public bool $skipRestrictionCheck = false;
    public ?string $comment = null;
    public string $content;
    public ?string $reference = null;

    public function __construct(ShipmentType $shipmentType)
    {
        $this->shipper = new ShipperAddress();
        $this->receiver = new ReceiverAddress();
        $this->service = new ServiceDefinition($shipmentType);
        self::setShipmentDate();
    }

    public function setShipmentDate(?Carbon $shipmentDate = null): static
    {
        if (empty($shipmentDate)) $shipmentDate = now();
        $this->shipmentDate = $shipmentDate->format('Y-m-d');
        return $this;
    }

    public function setShipmentContent(string $content): static
    {
        $this->content = str($content)->limit(30, '');
        return $this;
    }

    public function setComment(string $comment): static
    {
        $this->comment = str($comment)->limit(100, '');
        return $this;
    }

    public function setReference(string $reference): static
    {
        $this->reference = str($reference)->limit(200, '');
        return $this;
    }

    public function addItem(ShipmentItemType $type = ShipmentItemType::PACKAGE): Item
    {
        $item = new Item($type);
        $this->pieceList[] = $item;
        return $item;
    }

    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }
}
