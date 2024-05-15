<?php

namespace xGrz\Dhl24\Wizard;

use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DomesticShipmentType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLItem;
use xGrz\Dhl24\Models\DHLShipment;

class ShipmentWizard
{
    private DHLShipment $shipment;

    public function __construct(?DHLShipment $shipment = null)
    {
        $this->shipment = $shipment ?? new DHLShipment();
    }

    public function store(): static
    {
        $this->shipment->save();
        return $this;
    }

    public function setShipperName(string $name): static
    {
        $this->shipment->shipper_name = $name;
        return $this;
    }

    public function setShipperPostalCode(string $postalCode): static
    {
        $this->shipment->shipper_postal_code = $postalCode;
        return $this;
    }

    public function setShipperCity(string $city): static
    {
        $this->shipment->shipper_city = $city;
        return $this;
    }

    public function setShipperStreet(string $street): static
    {
        $this->shipment->shipper_street = $street;
        return $this;
    }

    public function setShipperHouseNumber(string $houseNumber): static
    {
        $this->shipment->shipper_house_number = $houseNumber;
        return $this;
    }

    public function setShipperContactPerson(string $contactPerson): static
    {
        $this->shipment->shipper_contact_person = $contactPerson;
        return $this;
    }

    public function setShipperContactPhone(string $contactPhone): static
    {
        $this->shipment->shipper_contact_phone = $contactPhone;
        return $this;
    }

    public function setShipperContactEmail(string $contactEmail): static
    {
        $this->shipment->shipper_contact_email = $contactEmail;
        return $this;
    }


    public function setReceiverType(DHLAddressType $addressType): static
    {
        $this->shipment->receiver_type = $addressType;
        return $this;
    }

    public function setReceiverName(string $name): static
    {
        $this->shipment->receiver_name = $name;
        return $this;
    }

    public function setReceiverPostalCode(string $postalCode, string $country = 'PL'): static
    {
        $this->shipment->receiver_country = $country;
        $this->shipment->receiver_postal_code = $postalCode;
        return $this;
    }

    public function setReceiverCity(string $city): static
    {
        $this->shipment->receiver_city = $city;
        return $this;
    }

    public function setReceiverStreet(string $street): static
    {
        $this->shipment->receiver_street = $street;
        return $this;
    }

    public function setReceiverHouseNumber(string $houseNumber): static
    {
        $this->shipment->receiver_house_number = $houseNumber;
        return $this;
    }

    public function setReceiverContactPerson(string $contactPerson): static
    {
        $this->shipment->receiver_contact_person = $contactPerson;
        return $this;
    }

    public function setReceiverContactPhone(string $contactPhone): static
    {
        $this->shipment->receiver_contact_phone = $contactPhone;
        return $this;
    }

    public function setReceiverContactEmail(string $contactEmail): static
    {
        $this->shipment->receiver_contact_email = $contactEmail;
        return $this;
    }


    public function addItem(DHLItem $item): static
    {
        $this->shipment->items->add($item);
        return $this;
    }


    public function setShipmentType(DomesticShipmentType $shipmentType): static
    {
        $this->shipment->product = $shipmentType;
        return $this;
    }

    public function setCollectOnDelivery(int|float $amount, string $reference = null): static
    {
        $this->shipment->collect_on_delivery = $amount;
        if ($reference) $this->shipment->collect_on_delivery_reference = $reference;
        if (!$this->shipment->insurance || $this->shipment->insurance < $amount) {
            $this->shipment->insurance = $amount;
        }
        return $this;
    }

    public function setShipmentValue(int|float $amount): static
    {
        if ($this->shipment->collect_on_delivery && $this->shipment->collect_on_delivery > $amount) {
            $this->shipment->insurance = $this->shipment->collect_on_delivery;
        } else {
            $this->shipment->insurance = $amount;
        }
        return $this;
    }

    public function setContent(string $content): static
    {
        $this->shipment->content = $content;
        return $this;
    }

    public function setCostCenter(DHLCostCenter $costCenter): static
    {
        $this->shipment->cost_center()->associate($costCenter);
        return $this;
    }

    /**
     * @return void
     * @throw DHL24Exception
     */
    public function create()
    {
        $shipmentId = DHL24::createShipment($this->shipment)?->getShipmentId();
        $this->shipment->number = $shipmentId;
        $this->shipment->save();
        $this->shipment->refresh();
        return $shipmentId;
    }

    public function getPayload(): array
    {
        return $this->shipment->getPayload();
    }
}
