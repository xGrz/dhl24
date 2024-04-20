<?php

namespace xGrz\Dhl24\Wizard\Components;

use Illuminate\Support\Carbon;
use xGrz\Dhl24\Traits\Arrayable;
use xGrz\Dhl24\Wizard\Components\Address\ReceiverAddress;
use xGrz\Dhl24\Wizard\Components\Address\ShipperAddress;

class Shipment
{
    use Arrayable;

    public ShipperAddress $shipper;
    public ReceiverAddress $receiver;
    public PieceList $pieceList;
    public ServiceDefinition $service;
    public PaymentData $payment;
    public string $shipmentDate;
    public bool $skipRestrictionCheck = false;
    public ?string $comment = null;
    public string $content = '';
    public ?string $reference = null;

    public function __construct()
    {
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


}
